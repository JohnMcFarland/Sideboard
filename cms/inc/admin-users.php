<!-- The user table is implemented here and displays on page load (see admin.js for logic) -->

<div class="container" id="user" style="padding: 50px 0;">
  <div class="col-xs-11 col-sm-11 col-md-11 col-lg-11" id="admin-users" style="background: #fff; height: auto; margin: 0 auto; overflow-x: scroll;">
      <table id="user-table" class="table table-hover nowrap" style="padding-top: 10px; width: 100%;">
        <thead style="background-color: #9B9B9B; color: white">
          <tr>
            <td><input type="checkbox" name="bulk-checkbox-user" /></td>
            <th>UID</th>
            <th>Status</th>
            <th>First</th>
            <th>Last</th>
            <th>Email</th>
            <th>Zip Code</th>
            <th>Birthday</th>
            <th>Cell Phone</th>
            <th>Profile Page</th>
            <th>HID</th>
            <th>Role</th>
            <th>Valid</th>
          </tr>
        </thead>

        <tbody>
          <?php
          $users = get_users();
          foreach( $users as $user ) { ?>
            <tr>
              <td name="checkbox" data-uid="<?php echo $user['ID']; ?>"><input type="checkbox" class="user-checkbox" /></td>
              <td name="uid" data-uid="<?php echo $user['ID']; ?>"><?php echo $user['ID']; ?></td>
              <td name="active" data-uid="<?php echo $user['ID']; ?>"><?php echo $user['active'] ? "Active" : "Deactive"; ?></td>
              <td name="firstname" data-uid="<?php echo $user['ID']; ?>"><?php echo $user['firstname']; ?></td>
              <td name="lastname" data-uid="<?php echo $user['ID']; ?>"><?php echo $user['lastname']; ?></td>
              <td name="email" data-uid="<?php echo $user['ID']; ?>"><?php echo $user['email']; ?></td>
              <td name="zip" data-uid="<?php echo $user['ID']; ?>"><?php echo empty($user['zip_code']) ? '?????' : $user['zip_code'] ; ?></td>
              <td name="birthday" data-uid="<?php echo $user['ID']; ?>"><?php echo $user['birthday']; ?></td>
              <td name="telephone" data-uid="<?php echo $user['ID']; ?>"><?php echo empty($user['telephone_cell']) ? '(???)-???-????' : $user['telephone_cell'] ; ?></td>
              <td name="profile-page-name" data-uid="<?php echo $user['ID']; ?>"><a href="<?php echo get_profile_url( $user['ID'] ); ?>"><?php echo $user['profile_page_name']; ?></a></td>
              <td name="identifier" data-uid="<?php echo $user['ID']; ?>"><?php echo $user['identifier']; ?></td>
              <td name="admin" data-uid="<?php echo $user['ID']; ?>"><?php echo $user['admin'] ? 'Admin' : 'User'; ?></td>
              <td name="valid" data-uid="<?php echo $user['ID']; ?>"><?php echo $user['valid'] ? "Yes" : "No"; ?></td>
            </tr>
          <?php } ?>
        </tbody>

      </table>

  </div>

<!-- Bulk action dropdown menu with apply button (see admin.js for logic) -->

  <div class="row">
    <div class="col-xs-3 col-sm-3 col-md-3 col-lg-3" id="user-button" style="padding-top: 15px; margin-left: 25px;">
      <select name="user-table-bulk-options" style="-webkit-appearance: menulist-button; height: 35px; background: #fff;">
        <option data-user-action="admin-user-null">Bulk Actions</option>
        <option data-user-action="admin-user-null">Suspend</option>
        <option data-user-action="admin-user-null">Deactivate</option>
        <option data-user-action="admin-user-null">Activate</option>
        <option data-user-action="admin-user-delete">Delete</option>
      </select>

      <button type="button" name="admin-user-apply" class="btn btn-primary">Apply</button>
    </div>
  </div>
</div>
