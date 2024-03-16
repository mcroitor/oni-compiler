<article class="u-full-width container">
    <div class="six columns">
        <h2>Contestants</h2>
                <table class='u-full-width'>
            <caption></caption>
            <thead>
                <tr>
                    <th>id</th>
                    <th>Last Name</th>
                    <th>First Name</th>
                </tr>
            </thead>
            <tbody>
                <!-- in-contest-users -->
            </tbody>
        </table>
    </div>
    <div class="six columns">
        
    <h2>Users</h2>
    <form method="post" action="/?q=contest/addparticipants/<!-- contest-id -->">
        <table class='u-full-width'>
            <caption></caption>
            <thead>
                <tr>
                    <th>select</th>
                    <th>Last Name</th>
                    <th>First Name</th>
                </tr>
            </thead>
            <tbody>
                <!-- out-contest-users -->
            </tbody>
        </table>
        <input type="submit" name="add-participants" value="enroll users" />
    </form>
    </div>
</article>