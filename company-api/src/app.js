const express = require('express');
const cors = require('cors');
const helmet = require('helmet');
const morgan = require('morgan');
require('dotenv').config();

const app = express();
const PORT = process.env.PORT || 3000;

// Middleware de sécurité
app.use(helmet());
app.use(cors({
  origin: process.env.CORS_ORIGIN?.split(',') || ['http://localhost:4200'],
  credentials: true
}));

// Middleware de logging
app.use(morgan('combined'));

// Middleware de parsing
app.use(express.json({ limit: '10mb' }));
app.use(express.urlencoded({ extended: true, limit: '10mb' }));

// Routes
app.use('/api/quote', require('./routes/quote'));
app.use('/api/issue', require('./routes/issue'));
app.use('/api/renew', require('./routes/renew'));
app.use('/api/verify-qr', require('./routes/verify-qr'));
app.use('/api/webhook-payment', require('./routes/webhook-payment'));
app.use('/api/health', require('./routes/health'));

// Middleware de gestion d'erreurs
app.use((err, req, res, next) => {
  console.error('Erreur:', err);
  
  res.status(err.status || 500).json({
    success: false,
    message: err.message || 'Erreur interne du serveur',
    ...(process.env.NODE_ENV === 'development' && { stack: err.stack })
  });
});

// Route 404
app.use('*', (req, res) => {
  res.status(404).json({
    success: false,
    message: 'Route non trouvée'
  });
});

// Démarrage du serveur
app.listen(PORT, () => {
  console.log(`🚀 Company API démarrée sur le port ${PORT}`);
  console.log(`📊 Environnement: ${process.env.NODE_ENV}`);
  console.log(`🔗 URL: http://localhost:${PORT}`);
});

module.exports = app;

