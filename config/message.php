<?php

return [
    //Field Input Messages
    'email_empty' => 'Email must not be blank.',
    'invalid_email' => 'Your email is invalid. PLease try again.',

    'password_empty' => 'Password must not be blank.',
    'invalid_password' => 'Your password must contain at least 8 characters.\n It must contain at least 1 digit, 1 character.\n Please try again.',

    'name_empty' => 'Name must not be blank.',
    'invalid_name' => 'Name must only contain letters.',

    'role_empty' => 'Role must be selected.',
    'invalid_role' => 'Role selection is invalid.',

    'status_empty' => 'Status must be selected.',
    'invalid_status' => 'Status selection is invalid.',

    'avatar_empty' => 'Avatar must not be blank.',
    'invalid_avatar' => 'Avatar type is invalid.',
    'avatar_error' => 'Avatar is broken or error. Try again.',
    'avatar_over_size' => 'Avatar size must be <= 2MB',

    'verify_password' => 'Your Password Verify must match your password.',

    'invalid_id' => 'ID must only contain numbers',
    'no_id_found' => 'Must provide a valid ID!',

    //Module - Controller interaction Messages
    'create_success' => 'New account has been successfully created!',
    'create_failed' => 'Failed to create new account.',
    'update_success' => 'Successfully updated!',
    'update_failed' => ' Failed to updated!',
    'delete_success' => 'Delete success!',
    'delete_failed' => 'Delete failed!',
    'search_success' => "Search completed!",

    //Permission
    'no_permission_admin' => 'You do not have ADMIN permission to process this action',
    'no_permission_super_admin' => 'You do not have SUPER ADMIN permission to process this action',

    //Login Message
    'common_error' => 'Your email and password might be incorrect.',
    'invalid_account_status' => 'You has been banned. Contact for support.',
    'login_success' => ' has been logged in.',
    'login_failed' => 'Your email or password might be incorrect.',
    'login_fb_failed' => 'Failed to login with faceboook!',
    'logout' => 'You have been logged out!'
];