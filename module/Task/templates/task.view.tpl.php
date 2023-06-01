<script src="./scripts/showdown.js"></script>
<article class="container">
    <h3 class="header row"><!-- task-name --></h3>
    <div class="body row">
        <div id="task-description"><!-- task-description --></div>
    </div>
    <h4 class="subtitle row">Limits</h4>
    <div class="footer row">
        <table class='u-full-width'>
            <caption></caption>
            <tbody>
                <tr>
                    <th>time limit, ms</th>
                    <td><!-- task-time --></td>
                </tr>
                <tr>
                    <th>memory limit, mb</th>
                    <td><!-- task-memory --></td>
                </tr>
            </tbody>
        </table>
    </div>
</article>
<div class="container">
    <a href="/?q=task/list" class="button">Back to tasks</a>
</div>
<script>
function get(id) {
    return document.getElementById(id);
}
var converter = new showdown.Converter();

get("task-description").innerHTML = converter.makeHtml(get("task-description").innerHTML);
</script>