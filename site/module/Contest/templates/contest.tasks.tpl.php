<article class="container">
    <h2>Tasks in contest</h2>
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
            <!-- in-contest-tasks -->
        </tbody>
    </table>
    <h2>Tasks out of contest</h2>
    <form method="post" action="/?q=contest/addtasks/<!-- contest-id -->">
        <table class='u-full-width'>
            <caption></caption>
            <thead>
                <tr>
                    <th>select</th>
                    <th>name</th>
                    <th>time</th>
                    <th>memory</th>
                </tr>
            </thead>
            <tbody>
                <!-- out-contest-tasks -->
            </tbody>
        </table>
        <input type="submit" name="add-tasks" value="Add tasks" />
    </form>
</article>