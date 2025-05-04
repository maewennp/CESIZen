
export const API_ENDPOINTS = {
  AUTH: {
    LOGIN: '/auth/login.php',
    REGISTER: '/auth/register.php',
    FORGOT_PASSWORD: '/auth/forgotPassword.php',
    RESET_PASSWORD: '/auth/resetPassword.php',
  },
  USERS: {
    GET_PROFILE: '/users/profile.php',
    GET_ALL: '/users/getAllUsers.php',
    GET_ONE: '/users/getOneUser.php',
    UPDATE: '/users/updateUser.php',
    DELETE: '/users/deleteUser.php',
    TOGGLE_ACTIVE: '/users/toggleIsActiveUser.php',
    CHANGE_PASSWORD:'/users/changePassword.php',
  },
  INFO: {
    GET_ALL_VISIBLE: '/info/getAllInfos.php',
    GET_ONE: '/info/getOneInfo.php',
    ADMIN_GET_ALL: '/info/adminGetAllInfos.php',
    CREATE: '/info/createInfo.php',
    UPDATE: '/info/updateInfo.php',
    DELETE: '/info/deleteInfo.php',
    TOGGLE_VISIBILITY: '/info/toggleVisibilityInfo.php',
  },
  RELAX_ACTIVITY: {
    GET_ALL_ACTIVE: '/relaxActivity/getAllActiveRelaxActivities.php',
    GET_ONE: '/relaxActivity/getOneRelaxActivity.php',
    ADMIN_GET_ALL: '/relaxActivity/adminGetAllRelaxActivities.php',
    CREATE: '/relaxActivity/createRelaxActivity.php',
    UPDATE: '/relaxActivity/updateRelaxActivity.php',
    DELETE: '/relaxActivity/deleteRelaxActivity.php',
    TOGGLE_ACTIVE: '/relaxActivity/toggleActiveRelaxActivity.php',
  }
};
