import { useState } from 'preact/hooks'

const demoAccount = {
  username: 'admin@gmail.com',
  password: 'admin123',
}

export function Login({ onLogin }) {
  const [username, setUsername] = useState('')
  const [password, setPassword] = useState('')
  const [error, setError] = useState('')

  function handleSubmit(event) {
    event.preventDefault()

    if (!username.includes('@')) {
      setError('Username harus menggunakan @')
      return
    }

    if (username === demoAccount.username && password === demoAccount.password) {
      setError('')
      onLogin({ username })
      return
    }

    setError('Username atau password salah')
  }

  return (
    <main className="app-shell">
      <section className="login-panel" aria-labelledby="login-title">
        <div className="panel-header">
          <p className="eyebrow">Login</p>
          <h1 id="login-title">Masuk Aplikasi</h1>
        </div>

        <form className="login-form" onSubmit={handleSubmit}>
          <div className="form-group">
            <label htmlFor="username">Username</label>
            <input
              id="username"
              type="text"
              value={username}
              onInput={(event) => setUsername(event.currentTarget.value)}
              autoComplete="username"
              placeholder="admin@gmail.com"
            />
          </div>

          <div className="form-group">
            <label htmlFor="password">Password</label>
            <input
              id="password"
              type="password"
              value={password}
              onInput={(event) => setPassword(event.currentTarget.value)}
              autoComplete="current-password"
              placeholder="admin123"
            />
          </div>

          {error && <p className="login-error">{error}</p>}

          <button type="submit" className="login-button">
            Masuk
          </button>
        </form>
      </section>
    </main>
  )
}
