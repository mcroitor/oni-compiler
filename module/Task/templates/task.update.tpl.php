<form method="post" action="/?q=task/update">
    <input type="hidden" name="task-id" id="task-id" value="<!-- task-id -->" />
    <fieldset>
        <table class='u-full-width'>
            <caption></caption>
            <tr>
                <th colspan="2">
                    <legend>task definition</legend>
                </th>
            </tr>
            <tr>
                <td class="three">
                    <label for="task-name">name</label>
                </td>
                <td class="nine">
                    <input type="text" name="task-name" id="task-name"
                        class='u-full-width' value="<!-- task-name -->" />
                </td>
            </tr>
            <tr>
                <td>
                    <label for="task-description">description</label>
                </td>
                <td>
                    <textarea name="task-description" id="task-description"
                        class='u-full-width'><!-- task-description --></textarea>
                </td>
            </tr>
            <tr>
                <td>
                    <label for="task-memory">memory, MB</label>
                </td>
                <td>
                    <input type="number" min="1" max="256" value="<!-- task-memory -->"
                        name="task-memory" id="task-memory" class='u-full-width' />
                </td>
            </tr>
            <tr>
                <td>
                    <label for="task-time">time, ms</label>
                </td>
                <td>
                    <input type="number" min="100" max="10000" value="<!-- task-time -->"
                        name="task-time" id="task-time" class='u-full-width' />
                </td>
            </tr>
        </table>
    </fieldset>
    <input type="submit" name="update-task" value="Update" />
    <a href="/?q=task/tests/<!-- task-id -->" class="button">Update tests</a>
</form>