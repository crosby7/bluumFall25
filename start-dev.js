#!/usr/bin/env node

const { spawn } = require('child_process');
const path = require('path');
const os = require('os');
const http = require('http');

/**
 * Detect the local network IP address
 * @returns {string} The local IP address
 */
function getLocalIP() {
  const interfaces = os.networkInterfaces();

  for (const name of Object.keys(interfaces)) {
    for (const iface of interfaces[name]) {
      // Skip internal (loopback) and non-IPv4 addresses
      if (iface.family === 'IPv4' && !iface.internal) {
        return iface.address;
      }
    }
  }

  // Fallback to localhost if no network interface found
  return '127.0.0.1';
}

/**
 * Check if the Laravel server is accessible
 * @param {string} url The URL to check
 * @returns {Promise<{success: boolean, error?: string}>} Health check result
 */
function checkServerHealth(url) {
  return new Promise((resolve) => {
    const urlObj = new URL(url);
    const options = {
      hostname: urlObj.hostname,
      port: urlObj.port,
      path: '/',
      method: 'GET',
      timeout: 3000,
    };

    const req = http.request(options, (res) => {
      // Any response (even 404) means the server is running
      resolve({ success: true });
    });

    req.on('error', (err) => {
      resolve({ success: false, error: err.message });
    });

    req.on('timeout', () => {
      req.destroy();
      resolve({ success: false, error: 'Connection timeout' });
    });

    req.end();
  });
}

const localIP = getLocalIP();
const API_URL = `http://${localIP}:8000/api`;

console.log('='.repeat(60));
console.log('Starting Bluum Development Environment');
console.log('='.repeat(60));
console.log(`\nDetected IP: ${localIP}`);
console.log(`API URL: ${API_URL}\n`);
console.log('='.repeat(60));
console.log();

// Start Laravel server
console.log('Starting Laravel server...\n');
const laravel = spawn('php', ['artisan', 'serve', '--host=0.0.0.0', '--port=8000'], {
  cwd: path.join(__dirname, 'server-laravel'),
  shell: true,
  stdio: 'inherit'
});

laravel.on('error', (err) => {
  console.error('Error starting Laravel:', err);
  process.exit(1);
});

// Wait for Laravel to start, then verify health and start Expo
async function startExpo() {
  console.log('\nWaiting for Laravel server to be ready...');

  // Poll for server health
  let attempts = 0;
  const maxAttempts = 10;
  const serverUrl = `http://${localIP}:8000`;

  while (attempts < maxAttempts) {
    const result = await checkServerHealth(serverUrl);

    if (result.success) {
      console.log(`\n${'='.repeat(60)}`);
      console.log('✓ Laravel server is ready!');
      console.log(`Server accessible at: ${serverUrl}`);
      console.log(`API URL: ${API_URL}`);
      console.log(`${'='.repeat(60)}\n`);
      console.log('IMPORTANT: If your tablet cannot connect:');
      console.log('  1. Make sure your tablet is on the same WiFi network');
      console.log('  2. Add Windows Firewall rule (run as Administrator):');
      console.log(`     netsh advfirewall firewall add rule name="Laravel Dev Server" dir=in action=allow protocol=TCP localport=8000`);
      console.log(`${'='.repeat(60)}\n`);
      break;
    }

    attempts++;
    if (attempts < maxAttempts) {
      process.stdout.write('.');
      await new Promise(resolve => setTimeout(resolve, 1000));
    } else {
      console.error('\n\nWARNING: Could not verify Laravel server health!');
      console.error(`Error: ${result.error || 'Unknown'}`);
      console.error(`Attempted to reach: ${serverUrl}`);
      console.error('Continuing anyway...\n');
    }
  }

  console.log('Starting Expo...\n');

  const expo = spawn('npm', ['run', 'fresh-start'], {
    cwd: path.join(__dirname, 'BluumApp'),
    shell: true,
    stdio: 'inherit',
    env: {
      ...process.env,
      EXPO_PUBLIC_API_URL: API_URL
    }
  });

  expo.on('close', (code) => {
    console.log(`\nExpo stopped with code ${code}`);
    laravel.kill();
    process.exit(code);
  });

  expo.on('error', (err) => {
    console.error('Error starting Expo:', err);
    laravel.kill();
    process.exit(1);
  });

  // Handle script termination
  process.on('SIGINT', () => {
    console.log('\n\nShutting down...');
    expo.kill();
    laravel.kill();
    process.exit(0);
  });
}

// Start the health check and Expo startup process
setTimeout(startExpo, 2000);
