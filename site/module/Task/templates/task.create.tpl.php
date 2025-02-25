<script src="./scripts/smde/simplemde.min.js"></script>
<form method="post" action="/?q=task/create">
    <fieldset>
        <table class='u-full-width'>
            <caption>task definition</caption>
            <tr>
                <td class="three">
                    <label for="task-name">name</label>
                </td>
                <td class="nine">
                    <input type="text" name="task-name" id="task-name" class='u-full-width' />
                </td>
            </tr>
            <tr>
                <td>
                    <label for="task-description">description</label>
                </td>
                <td>
                    <textarea name="task-description" id="task-description" class='u-full-width'></textarea>
                </td>
            </tr>
            <tr>
                <td>
                    <label for="task-memory">memory, MB</label>
                </td>
                <td>
                    <input type="number" min="1" max="256" value="8"
                        name="task-memory" id="task-memory" class='u-full-width' />
                </td>
            </tr>
            <tr>
                <td>
                    <label for="task-time">time, ms</label>
                </td>
                <td>
                    <input type="number" min="100" max="10000" value="1000"
                        name="task-time" id="task-time" class='u-full-width' />
                </td>
            </tr>
        </table>
    </fieldset>
    <input type="submit" name="create-task" value="Create" />
</form>
<script>
var simplemde = new SimpleMDE({ element: document.getElementById("task-description") });
</script>