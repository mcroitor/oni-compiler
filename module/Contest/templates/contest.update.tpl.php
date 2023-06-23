<form method="post" action="/?q=contest/update">
    <input type="hidden" name="contest-id" id="contest-id" value="<!-- contest-id -->" />
    <fieldset>
        <table class='u-full-width'>
            <caption></caption>
            <tr>
                <th colspan="2">
                    <legend>contest definition</legend>
                </th>
            </tr>
            <tr>
                <td>
                    <label for="contest-name">name</label>
                </td>
                <td>
                    <input type="text" name="contest-name" id="contest-name"
                        class='u-full-width' value="<!-- contest-name -->" />
                </td>
            </tr>
            <tr>
                <td>
                    <label for="contest-description">description</label>
                </td>
                <td>
                    <textarea name="contest-description" id="contest-description"
                        class='u-full-width'><!-- contest-description --></textarea>
                </td>
            </tr>
            <tr>
                <td>
                    <label for="contest-start">start</label>
                </td>
                <td>
                    <input type="datetime-local" value="<!-- contest-start -->"
                        name="contest-start" id="contest-start" class='u-full-width' />
                </td>
            </tr>
            <tr>
                <td>
                    <label for="contest-end">end</label>
                </td>
                <td>
                    <input type="datetime-local" value="<!-- contest-end -->"
                        name="contest-end" id="contest-end" class='u-full-width' />
                </td>
            </tr>
        </table>
        <div class="footer row">
            <h4 class="subtitle row">Tasks</h4>
            <table class='u-full-width'>
                <caption></caption>
                <thead>
                    <tr>
                        <th>id</th>
                        <th>name</th>
                        <th>time</th>
                        <th>memory</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- tasks -->
                </tbody>
            </table>
        </div>
    </fieldset>
    <input type="submit" name="update-contest" value="Update" />
    <a href="/?q=contest/tasks/<!-- contest-id -->" class="button">Add tasks</a>
    <a href="/?q=contest/list" class="button">Back to contests</a>
</form>