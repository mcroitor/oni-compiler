<form method="post" action="/?q=contest/solutions/<!-- contest-id -->" enctype="multipart/form-data">
    <div class="row">
        <div class="twelve columns">
            <h3>Import Solutions</h3>
            <p>Upload a zip file containing the solutions.</p>
            <input type="file" name="file" accept=".zip" required />
        </div>
    </div>
    <div class="row">
        <div class="twelve columns">
            <button type="submit" class="button">Import</button>
        </div>
    </div>
    <div class="row">
        <div class="twelve columns">
            <p><strong>Note:</strong> The zip file should contain a directory with the solutions.</p>
            <p>Example structure:</p>
            <pre style="line-height: 1.2; padding: 5px 50px; border: 1px solid #ccc;">
    contest-solutions.zip
    ├── participant1
    │   ├── task1.cpp
    │   ├── task2.cpp
    │   └── task3.cpp
    └── participant2
        ├── task1.cpp
        ├── task2.cpp
        └── task3.cpp
</pre>
            <p>Make sure to follow the structure above for successful import.</p>
        </div>
    </div>
</form>