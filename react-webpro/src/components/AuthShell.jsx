import { Link } from 'react-router-dom';
import AppIcon from './AppIcon';

function AuthShell({ title, subtitle, sideTitle, sideText, children, footerPrompt, footerLink, footerLabel }) {
  return (
    <div className="auth-page">
      <div className="auth-shell">
        <div className="auth-background">
          <div className="auth-hero-card">
            <div className="auth-brand-lockup">
              <div className="brand-badge">
                <div className="brand-badge__glow" />
                <div className="brand-badge__icon">CV</div>
              </div>
              <div>
                <h1>
                  Keamanan Siber
                  <span>Untuk Semua Orang</span>
                </h1>
                <p>Lindungi data pribadi, laporkan insiden siber, dan pantau keamanan digital Anda secara real-time.</p>
              </div>
            </div>
            <div className="cyber-artwork">
              <div className="cyber-artwork__fog" />
              <div className="cyber-artwork__shield">
                <AppIcon name="shield" className="cyber-artwork__shield-icon" />
              </div>
              <div className="cyber-card cyber-card--left">
                <AppIcon name="user" className="cyber-card__icon" />
              </div>
              <div className="cyber-card cyber-card--bottom">
                <AppIcon name="lock" className="cyber-card__icon" />
              </div>
              <div className="cyber-card cyber-card--right">
                <AppIcon name="article" className="cyber-card__icon" />
              </div>
              <div className="cyber-chip cyber-chip--wifi">
                <AppIcon name="wifi" className="cyber-chip__icon" />
              </div>
              <div className="cyber-chip cyber-chip--globe">
                <AppIcon name="globe" className="cyber-chip__icon" />
              </div>
            </div>
          </div>

          <div className="auth-panel">
            <div className="auth-panel__head">
              <h2>{title}</h2>
              <p>{subtitle}</p>
            </div>

            {children}

            {footerPrompt ? (
              <p className="auth-panel__footer">
                {footerPrompt} <Link to={footerLink}>{footerLabel}</Link>
              </p>
            ) : null}
          </div>
        </div>

        <footer className="auth-footer">
          <span>@ 2026 CyberVault dilindungi Hak Cipta</span>
          <div>
            <a href="#privacy">Kebijakan Privasi</a>
            <a href="#terms">Syarat & Ketentuan</a>
            <a href="#help">Bantuan</a>
          </div>
        </footer>
      </div>
    </div>
  );
}

export default AuthShell;
