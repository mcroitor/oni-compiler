<form method="post" action="/?q=task/update/<!-- id -->" name="task">
    <table>
        <thead>
            <tr>
                <th>Field</th>
                <th>Value</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <th>Task Name</th>
                <td><input type="text" id="name" name="name" class="input" value="<!-- name -->" /></td>
            </tr>
            <tr>
                <th>Task memory, MB</th>
                <td><input type="number" id="memory" name="memory" class="button" min="1" max="256" value="<!-- memory -->" /></td>
            </tr>
            <tr>
                <th>Task time, ms</th>
                <td><input type="number" id="time" name="time" class="button" min="100" max="10000" value="<!-- time -->" /></td>
            </tr>
            <tr>
                <td><input type="submit" /></td>
            </tr>
        </tbody>
    </table>
</form>