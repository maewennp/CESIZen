export interface RelaxActivity {
  id_activity: number
  activity_label: string
  content: string
  category: string
  type: string
  media_activity?: string
  is_active?: boolean
  created_at?: string
}

// Payload pour créer une activité
export interface CreateRelaxActivityPayload {
  activity_label: string;
  content: string;
  category: string;
  type: string;
  media_activity?: string;
  is_active?: boolean;
}

// Payload pour mettre à jour une activité
export interface UpdateRelaxActivityPayload
  extends Partial<CreateRelaxActivityPayload> {
  id_activity: number;
}