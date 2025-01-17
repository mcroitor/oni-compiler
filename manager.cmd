@echo off
setlocal

rem it is a configuration / installation script
rem
rem usage: manager.cmd [help|init|install|start|meta|clean|rebuild]
rem     help    - display this help
rem     init    - initialize the environment
rem     install - install site dependencies, create database
rem     start   - start the server
rem     meta    - create the metadata
rem     clean   - remove dependencies and database
rem     rebuild - clean, meta, install

rem used php version
set PHP_VERSION=8.1.27
rem used gcc version
set GCC_VERSION=13.2.0
rem project directory
set PROJECT_DIR=%cd%
rem use __tmp__ as temporary directory
set TMP_DIR=%PROJECT_DIR%\__tmp__
rem data directory
set DATA_DIR=%PROJECT_DIR%\data
rem tools directory
set TOOLS_DIR=%PROJECT_DIR%\tools

rem dependencies: 7z, wget
set WGET=wget.exe
set SEVEN_ZIP=7z.exe

rem set PHP and GCC environment variables
set PATH=%PATH%;%TOOLS_DIR%\php
set PATH=%PATH%;%TOOLS_DIR%\gcc\bin

rem check if wget --version can be executed
%WGET% --version 2>&1 >nul && (
    echo %WGET% found
) || (
    echo %WGET% not found
    goto :end
)
rem check if 7z --help can be executed
(%SEVEN_ZIP% --help 2>&1 >nul || (
    rem check path c:\Program Files\7-Zip
    if exist "c:\Program Files\7-Zip\7z.exe" (
        set SEVEN_ZIP="c:\Program Files\7-Zip\7z.exe"
    ) else (
        rem check path c:\Program Files (x86)\7-Zip
        if exist "c:\Program Files (x86)\7-Zip\7z.exe" (
            set SEVEN_ZIP="c:\Program Files (x86)\7-Zip\7z.exe"
        ) else (
            echo 7z not found
            goto :end
        )
    )
)) && (
    echo %SEVEN_ZIP% found
) || (
    echo %SEVEN_ZIP% not found
    goto :end
)

rem if no parameters are given or parameter is `help`, display help
if "%1" == "" goto :help
if "%1" == "help" goto :help

rem if parameter is `init`, initialize the environment
if "%1" == "init" goto :initialize

rem if parameter is `install`, install site dependencies, create database
if "%1" == "install" goto :install

rem if parameter is `start`, start the server
if "%1" == "start" goto :start

rem if parameter is `meta`, create the metadata
if "%1" == "meta" goto :meta

rem if parameter is `clean`, remove dependencies and database
if "%1" == "clean" goto :clean

rem if parameter is `rebuild`, clean, meta, install
if "%1" == "rebuild" goto :rebuild

rem if parameter is unknown, display help
:help
    echo "usage: manager.cmd [help|init|install|start|meta|clean|rebuild]"
    echo "    help    - display this help"
    echo "    init    - initialize the environment"
    echo "    install - install site dependencies, create database"
    echo "    start   - start the server"
    echo "    meta    - create the metadata"
    echo "    clean   - remove dependencies and database"
    echo "    rebuild - clean, meta, install"
    EXIT /B 0
goto :end

:initialize
    rem initialize the environment

    rem if php is not installed, download it and unpack it to the tools directory
    set PHP_URL=https://windows.php.net/downloads/releases/php-%PHP_VERSION%-nts-Win32-vs16-x64.zip
    set PHP_DIR=tools\php
    set GCC_URL=https://github.com/niXman/mingw-builds-binaries/releases/download/%GCC_VERSION%-rt_v11-rev0/i686-%GCC_VERSION%-release-posix-dwarf-msvcrt-rt_v11-rev0.7z

    rem make tmp dir
    if not exist %TMP_DIR% mkdir %TMP_DIR%

    echo check dependencies

    rem check if php exists in system
    php.exe --version 2>&1 >nul && (
        echo php found
    ) || (
        rem check if php exists in tools directory
        if exist %PHP_DIR%\php.exe (
            echo php found
        ) else (
            echo php not found
            echo download php
            %WGET% -O %TMP_DIR%\php.zip %PHP_URL%
            echo unpack php
            %SEVEN_ZIP% x -y -o%PHP_DIR% %TMP_DIR%\php.zip
        )
    )

    rem check if gcc exists in system
    gcc --version 2>&1 >nul && (
        echo gcc found
    ) || (
        rem check if gcc exists in tools directory
        if exist %TOOLS_DIR%\gcc\bin\gcc.exe (
            echo gcc found
        ) else (
            echo gcc not found
            echo download gcc
            %WGET% -O %TMP_DIR%\gcc.7z %GCC_URL%
            echo unpack gcc
            %SEVEN_ZIP% x -y -o%TOOLS_DIR%\gcc %TMP_DIR%\gcc.7z
        )
    )

    rem create data\contests\ data\tasks data\tmp\ directories
    echo create data directories
    if not exist %DATA_DIR%\contests\ mkdir %DATA_DIR%\contests\
    if not exist %DATA_DIR%\tasks\ mkdir %DATA_DIR%\tasks\
    if not exist %DATA_DIR%\tmp\ mkdir %DATA_DIR%\tmp\
    echo done

    EXIT /B 0
goto :end

:install
    php.exe --version 2>&1 >nul || (
        if not exist %PHP_DIR%\php.exe (
            echo php not found
            echo run `manager.cmd init` to download and install php
            goto :end
        )
    )
    echo install
    rem download and install site dependencies, create database
    set METADB="https://github.com/mcroitor/metadb/archive/refs/heads/main.zip"
    set MODMAN="https://github.com/mcroitor/module_manager/archive/refs/heads/main.zip"

    echo download tools
    %WGET% -O %TMP_DIR%\metadb.zip %METADB%
    %WGET% -O %TMP_DIR%\module_manager.zip %MODMAN%

    rem unpack metadb
    %SEVEN_ZIP% x -y -o%TMP_DIR% %TMP_DIR%\metadb.zip
    rem unpack modman
    %SEVEN_ZIP% x -y -o%TMP_DIR% %TMP_DIR%\module_manager.zip
    echo done

    rem update dependencies
    echo update dependencies
    php %TOOLS_DIR\module_manager\manager.php  --install --config=.\tools\modules.json
    echo done

    rem create database
    echo create database
    php %PROJECT_DIR%\site\cli\install.php
    echo done

    EXIT /B 0
goto :end

:start
    php.exe --version 2>&1 >nul || (
        if not exist %PHP_DIR%\php.exe (
            echo php not found
            echo run `manager.cmd init` to download and install php
            goto :end
        )
    )

    echo start server
    php -S localhost:8000 -t site

    EXIT /B 0
goto :end

:meta
    echo create metadata

    echo done
    EXIT /B 0
goto :end

:clean
    echo clean
    rem remove all from tmp directory
    echo remove tmp directory
    if exist %TMP_DIR% (rmdir /s /q %TMP_DIR% && mkdir %TMP_DIR%)
    rem remove all from data directory
    if exist %DATA_DIR% (rmdir /s /q %DATA_DIR% && mkdir %DATA_DIR%)
    EXIT /B 0
goto :end

:rebuild
    echo rebuild
    rem clean, meta, install
    call manager.cmd clean
    call manager.cmd meta
    call manager.cmd install
    EXIT /B 0
goto :end

:end
endlocal
