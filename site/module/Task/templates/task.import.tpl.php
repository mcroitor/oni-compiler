<form method="post" action="/?q=task/import" enctype="multipart/form-data">
    <fieldset>
        <legend>Task import form</legend>
        <input type="hidden" name="MAX_FILE_SIZE" value="30000000" />
        <input type="file" name="tests" accept=".zip" />
    </fieldset>
    <input type="submit" name="task-import" />
</form>