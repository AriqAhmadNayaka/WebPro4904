import { useEffect } from 'react'
import { useNavigate } from 'react-router-dom'

function Logout() {
  const navigate = useNavigate()

  useEffect(() => {
    sessionStorage.removeItem('cv-authenticated')
    navigate('/login', { replace: true })
  }, [navigate])

  return null
}

export default Logout
