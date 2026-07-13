import os

squoosh_dir = r"C:\Users\orian\code\squoosh-dev-gg"

inject_fetch_content = """// inject-fetch.js
const fs = require('fs');

globalThis.fetch = async function(url) {
  const urlStr = url.toString();
  let filePath = '';
  
  if (urlStr.startsWith('file://')) {
    filePath = urlStr.substring(7);
    if (process.platform === 'win32' && filePath.startsWith('/')) {
      filePath = filePath.substring(1);
    }
    filePath = decodeURIComponent(filePath);
  } else if (!urlStr.includes('://')) {
    filePath = urlStr;
  } else {
    throw new Error('Unsupported URL scheme: ' + urlStr);
  }

  try {
    const data = fs.readFileSync(filePath);
    const headers = new Headers();
    if (filePath.endsWith('.wasm')) {
      headers.set('content-type', 'application/wasm');
    }
    return new Response(data, {
      status: 200,
      headers
    });
  } catch (err) {
    console.error(`mock-fetch failed to read ${filePath}:`, err);
    throw err;
  }
};
"""

run_squoosh_content = """// run-squoosh.js
const worker_threads = require('worker_threads');

// Preload mock fetch in the main thread
require('./inject-fetch.js');

// Monkeypatch worker_threads to inject the preloader in child workers
const OriginalWorker = worker_threads.Worker;
class PatchedWorker extends OriginalWorker {
  constructor(filename, options) {
    const opts = options || {};
    opts.execArgv = opts.execArgv ? [...opts.execArgv] : [];
    opts.execArgv.push('--require', require.resolve('./inject-fetch.js'));
    super(filename, opts);
  }
}
worker_threads.Worker = PatchedWorker;

// Require and run Squoosh CLI (resolve correct cached path)
require('@squoosh/cli/src/index.js');
"""

with open(os.path.join(squoosh_dir, "inject-fetch.js"), "w", encoding="utf-8") as f:
    f.write(inject_fetch_content)

with open(os.path.join(squoosh_dir, "run-squoosh.js"), "w", encoding="utf-8") as f:
    f.write(run_squoosh_content)

print("[+] Successfully wrote inject-fetch.js and run-squoosh.js to squoosh-dev-gg directory.")
