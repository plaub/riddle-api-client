import type { FakeUserDTO, ResponseDTO, RiddleDTO } from '@/types/DTO'

const baseUrl = 'http://local.riddle.com:8000/api/'

export const api = {
  fetchRiddleList: async (): Promise<RiddleDTO[]> => {
    const response = await fetch(baseUrl + 'riddle/list')
    const res = (await response.json()) as ResponseDTO<RiddleDTO[]>

    if (res.status !== 200) {
      return []
    }

    return res.data
  },
  fetchFakeUser: async (): Promise<FakeUserDTO> => {
    const response = await fetch(baseUrl + 'fake/user')
    const res = (await response.json()) as ResponseDTO<FakeUserDTO>

    if (res.status !== 200) {
      return {
        UUID: '',
        username: '',
        email: ''
      }
    }

    return res.data
  },
  riddleCompleted: async (riddleId: string): Promise<void> => {
    const response = await fetch(baseUrl + `riddle/${riddleId}/complete`, {
      method: 'POST'
    })
    const res = (await response.json()) as ResponseDTO<void>

    if (res.status !== 200) {
      return
    }

    return res.data
  }
}
