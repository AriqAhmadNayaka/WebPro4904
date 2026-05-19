import { render } from 'preact'
import './index.css'
import { App } from './app.jsx'

// Menampilkan komponen App ke elemen <div id="app"></div> di index.html.
render(<App />, document.getElementById('app'))
