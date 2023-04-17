<form method="post" action="/?q=task/create">
    <fieldset>
        <legend>task definition</legend>
        <label for="task-name" />
        <input type="text" name="task-title" id="task-title" />
        <label for="task-description" />
        <textarea name="task-description" id="task-description"></textarea>
    </fieldset>
    <fieldset>
        <legend>tests</legend>
        <input type="file" name="task-tests" />
    </fieldset>
    <input type="submit" />
</form>