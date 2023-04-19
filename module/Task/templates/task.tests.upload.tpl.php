<form method="post" action="/?q=tests/upload" enctype="multipart/form-data">
    <fieldset>
        <legend>Tests upload form</legend>
        <input type="hidden" name="task-id" value="<!-- task-id -->" />
        <input type="hidden" name="MAX_FILE_SIZE" value="30000000" />
        <input type="file" name="tests" accept=".zip" />
        <label for="input-pattern">Input pattern</label>
        <input type="text" name="input-pattern" value="input*.txt" />
        <label for="output-pattern">Output pattern</label>
        <input type="text" name="output-pattern" value="output*.txt" />
        <label for="override">Override old tests</label>
        <input type="checkbox" name="override" />
    </fieldset>
    <input type="submit" name="tests-upload" />
</form>