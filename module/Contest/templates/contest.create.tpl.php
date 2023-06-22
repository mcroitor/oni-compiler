<form method="post" action="/?q=contest/create">
    <fieldset>
        <table class='u-full-width'>
            <caption></caption>
            <tr>
                <th colspan="2">
                    <legend>contest definition</legend>
                </th>
            </tr>
            <tr>
                <td class="three">
                    <label for="contest-name">name</label>
                </td>
                <td class="nine">
                    <input type="text" name="contest-name" id="contest-name" class='u-full-width' />
                </td>
            </tr>
            <tr>
                <td>
                    <label for="contest-description">description</label>
                </td>
                <td>
                    <textarea name="contest-description" id="contest-description" class='u-full-width'></textarea>
                </td>
            </tr>
            <tr>
                <td>
                    <label for="contest-start">start time</label>
                </td>
                <td>
                    <input type="datetime-local"
                        name="contest-start" id="contest-start" class='u-full-width' />
                </td>
            </tr>
            <tr>
                <td>
                    <label for="contest-end">end time</label>
                </td>
                <td>
                    <input type="datetime-local"
                        name="contest-end" id="contest-end" class='u-full-width' />
                </td>
            </tr>
        </table>
    </fieldset>
    <input type="submit" name="create-contest" value="Create" />
</form>