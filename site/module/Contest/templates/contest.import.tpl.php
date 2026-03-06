<form method="post" action="/?q=contest/import" enctype="multipart/form-data">
    <fieldset>
        <legend>Import contest</legend>
        <table class='u-full-width'>
            <tr>
                <td>
                    <label for="contest">Select ZIP file</label>
                </td>
                <td>
                    <input type="file" name="contest" id="contest" accept=".zip" />
                </td>
            </tr>
        </table>
    </fieldset>
    <input type="submit" name="import-contest" value="Import" />
    <a href="/?q=contest/list" class="button">Cancel</a>
</form>
