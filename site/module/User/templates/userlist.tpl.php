<div class="row">
    <div class="nine columns">
        <form enctype="multipart/form-data" method="post" action="/?q=user/import">
            <label for="csv_name">Upload users:</label>
            <input type="file" name="csv_file" />
            <input type="hidden" name="MAX_FILE_SIZE" value="300000" />
            <input type="submit" />
        </form>
    </div>
    <div class="three columns align-right">
        <a href="/?q=user/add" class='button'>Add user</a>
    </div>
</div>


<table class='u-full-width' id='data-table'>
    <thead>
        <tr>
            <th>lastname</th>
            <th>firstname</th>
            <th>institution</th>
            <th>email</th>
        </tr>
    </thead>
    <tbody>
        <!-- userlist element -->
    </tbody>
</table>