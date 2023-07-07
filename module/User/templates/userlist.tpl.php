<form enctype="multipart/form-data" method="post" action="/?q=user/import">
    <input type="hidden" name="MAX_FILE_SIZE" value="300000" />
    <label for="csv_name">CSV file with users</label>
    <input type="file" name="csv_file" class='u-full-width button' />
    <input type="submit" />
</form>

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