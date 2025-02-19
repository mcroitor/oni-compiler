<article class="container">
    <h3 class="header row">
        <!-- contest-name -->
        <a href="/?q=contest/update/<!-- contest-id -->" style="float:right">
            <img class="icon" src="./images/editing.png" alt="edit contest" />
        </a>
    </h3>
    <div class="body row">
        <div id="task-description"><!-- contest-description --></div>
        <h4 class="subtitle row">Additional information</h4>
        <table class='u-full-width'>
            <caption></caption>
            <tbody>
                <tr>
                    <th>start</th>
                    <td><!-- contest-start --></td>
                </tr>
                <tr>
                    <th>end</th>
                    <td><!-- contest-end --></td>
                </tr>
            </tbody>
        </table>
    </div>
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
</article>
<div class="container">
    <a href="/?q=contest/import/solutions" class="button">Import solutions</a>
    <a href="/?q=contest/list" class="button">Back to contests</a>
</div>