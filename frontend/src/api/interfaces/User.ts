export interface User {
  id_user: number
  username: string
  email: string
  password?: string
  is_admin: boolean
}

export interface ExtendedUser extends User {
  is_active?: boolean
  created_at?: string
}