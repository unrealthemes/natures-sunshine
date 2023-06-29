<div class="wrap">
    <form method="post" action="?page=static-strings-editor&strings-update">
        <table class="strings-table widefat importers striped wp-list-table fixed striped table-view-list posts">
            <thead>
            <tr>
                <th scope="col" id="key" class="manage-column col-key">Key</th>
                <th scope="col" id="key" class="manage-column col-title">Text</th>
                <th scope="col" id="key" class="manage-column col-add">
                    <span class="add">
                        <button type="button" class="addstring button-link editinline">Add string</button>
                     </span>
                </th>
            </tr>
            </thead>
            <tbody id="the-list">
            <?php foreach (App\Controllers\Admin\StaticStringsController::getStrings() as $key => $string): ?>
                <tr id="<?= $key; ?>" class="iedit author-self level-0 post-1 type-post status-publish format-standard hentry category-1">
                    <td class="key column-title has-row-actions column-primary page-title" data-colname="Title">
                        <div class="locked-info"><span class="locked-avatar"></span> <span class="locked-text"></span></div>
                        <span class="input-text-wrap">
                            <input type="text" class="ptitle" name="keys[]" value="<?= $key; ?>">
                        </span>
                    </td>
                    <td class="string column-title inline-edit-row has-row-actions column-primary page-title" data-colname="Title">
                        <div class="locked-info"><span class="locked-avatar"></span> <span class="locked-text"></span></div>
                        <span class="input-text-wrap">
                            <input type="text" class="ptitle" name="values[]" value="<?= $string; ?>">
                        </span>
                    </td>
                    <td class="remove column-title has-row-actions column-primary page-title" data-colname="Title">
                        <div class="locked-info"><span class="locked-avatar"></span> <span class="locked-text"></span></div>
                        <div class="row-actions"><span class="edit">
                             <span class="trash">
                                <button type="button" class="submitdelete button-link editinline" data-id="<?= $key; ?>" style="color: #b32d2e">Delete</button>
                             </span>
                        </div>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
            <tfoot>
            <tr>
                <th scope="col" class="manage-column col-key">
                    <span>Key</span>
                </th>
                <th scope="col" class="manage-column col-title">
                    <span>Text</span>
                </th>
                <th scope="col" class="manage-column col-add">
                     <span class="add">
                        <button type="button" class="addstring button-link editinline">Add string</button>
                     </span>
                </th>
            </tr>
            </tfoot>
        </table>
        <p>
            <button type="submit" class="save button button-primary">Save</button>
        </p>
    </form>
    <style>
        .strings-table {
            width: 100%;
        }

        .col-key {
            width: 10%;
        }

        .col-title {
            width: 80%;
        }

        .col-add {
            width: 10%;
            text-align: right !important;
        }
        
        .remove{
            text-align: right;
        }
    </style>
    <script>
        jQuery(document).ready(function ($){
            
            $('.submitdelete').click(function (){
                var id = $(this).data('id');
                $('#' + id).remove();
            });
            
            $('.addstring').click(function (){
                    $("#the-list tr:last").clone().appendTo("#the-list").find("input").val("");
            });
            
        });
    </script>
</div>