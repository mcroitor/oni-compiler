<form method="post" action="/?q=contests/create" name="contest">
    <table>
        <thead>
            <tr>
                <th>Field</th>
                <th>Value</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <th>Contest Name</th>
                <td><input type="text" id="contest-name" name="contest-name" class="input" /></td>
            </tr>
            <tr>
                <th>Contest Start</th>
                <td><input type="datetime-local" id="contest-start" name="contest-start" class="button" /></td>
            </tr>
            <tr>
                <th>Contest End</th>
                <td><input type="datetime-local" id="contest-end" name="contest-end" class="button" /></td>
            </tr>
            <tr>
                <td><input type="submit" /></td>
            </tr>
        </tbody>
    </table>
</form>