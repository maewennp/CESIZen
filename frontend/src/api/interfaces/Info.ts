export interface Info {
  id_content: number
  content_label: string
  body: string
  media_content?: string
  created_at: string
  visible?: boolean
}

// Payload (=data) pour la création
export interface CreateInfoPayload {
  content_label: string;
  body: string;
  media_content?: string;
}

// Payload pour la mise à jour
export interface UpdateInfoPayload extends Partial<CreateInfoPayload> {
  id_content: number;
  visible?: boolean;
}