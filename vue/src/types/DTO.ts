export interface ResponseDTO<T> {
  message: string
  status: number
  requested_url: string
  data: T
}

export interface RiddleDTO {
  UUID: string
  title: string
  type: string
}

export interface FakeUserDTO {
  UUID: string
  username: string
  email: string
}
