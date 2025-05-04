export interface RelaxActivity {
  id_activity: number
  activity_label: string
  content: string
  category: string
  type: string
  media_activity?: string | null
  is_active?: boolean
  created_at?: string
}