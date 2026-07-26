module.exports = {
  darkMode: 'class',
  content: ['./app/Views/**/*.php', './public/assets/js/**/*.js'],
  theme: {
    extend: {
      colors: {
        ink: '#071014',
        panel: '#0d171d',
        line: '#1e3139',
        primary: '#4ee1a0',
        accent: '#7c5cff',
        heat: '#ffb86b',
        paper: '#f8fbf9',
        muted: '#9fb4bc'
      },
      fontFamily: {
        display: ['Space Grotesk', 'Inter', 'system-ui', 'sans-serif'],
        body: ['Inter', 'system-ui', 'sans-serif']
      },
      borderRadius: {
        premium: '8px'
      },
      boxShadow: {
        glow: '0 0 40px rgba(78, 225, 160, .16)',
        panel: '0 24px 80px rgba(0, 0, 0, .22)'
      }
    }
  },
  plugins: []
};
