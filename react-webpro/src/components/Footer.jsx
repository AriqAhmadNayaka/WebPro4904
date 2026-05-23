const Footer = () => {
  return (
    <footer className="cyber-footer">
      <div className="footer-container">
        <div className="footer-section">
          <h3 className="footer-logo">CyberVault</h3>
          <p>Edukasi keamanan siber terpercaya untuk melindungi privasi Anda di era digital.</p>
        </div>
        <div className="footer-section">
          <h4>Support</h4>
          <ul>
            <li>Bandung, Indonesia</li>
            <li>support@cybervault.id</li>
          </ul>
        </div>
        <div className="footer-section">
          <h4>Social</h4>
          <div className="social-links">
            <a href="#" className="social-item">Instagram</a>
            <a href="#" className="social-item">GitHub</a>
          </div>
        </div>
      </div>
      <div className="footer-bottom">
        <p>&copy; 2026 CyberVault Project - S1 Sistem Informasi Kota Cerdas.</p>
      </div>
    </footer>
  );
};

export default Footer;
