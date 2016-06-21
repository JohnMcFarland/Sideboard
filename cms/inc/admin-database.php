<!-- The database table is implemented here, the 'hide' class ensures that this table won't display by default (see admin.js for logic) -->

<div class="container hide" id="database" style="padding: 50px 0;">
  <div class="col-xs-11 col-sm-11 col-md-11 col-lg-11" id="admin-database" style="background: #fff; height: auto; margin: 0 auto; overflow-x: scroll;">
    <table id="database-table" class="table table-hover nowrap" style="padding-top: 10px; width: 100%;">
      <thead style="background-color: #9B9B9B; color: white">
        <tr>
          <td><input type="checkbox" name="bulk-checkbox-database" /></td>
          <th>ID</th>
          <th>Query</th>
          <th>Time</th>
        </tr>
      </thead>

      <tbody>
        <tr>
          <td><input type="checkbox" class="database-table-checkbox" /></td>
          <td>1</td>
          <td>query here</td>
          <td>time here</td>
        </tr>
        <tr>
          <td><input type="checkbox" class="database-table-checkbox" /></td>
          <td>2</td>
          <td>query here</td>
          <td>time here</td>
        </tr>
        <tr>
          <td><input type="checkbox" class="database-table-checkbox" /></td>
          <td>3</td>
          <td>query here</td>
          <td>time here</td>
        </tr>
      </tbody>

    </table>

  </div>

  <!-- Bulk action dropdown menu with apply button (see admin.js for logic) -->

  <div class="row">
    <div class="col-xs-3 col-sm-3 col-md-3 col-lg-3" id="database-button" style="padding-top: 15px; margin-left: 25px;">
      <select name="database-table-bulk-options" style="-webkit-appearance: menulist-button; height: 35px; background: #fff;">
        <option>Bulk Actions</option>
        <option>Suspend</option>
        <option>Deactivate</option>
        <option>Activate</option>
        <option data-id="0">Delete</option>
      </select>

      <button type="button" name="admin-apply" class="btn btn-primary">Apply</button>
    </div>
  </div>
</div>
