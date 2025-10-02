#!/usr/bin/env node

/**
 * Preview Image Generator
 * 
 * Uses Playwright to generate high-quality preview images from HTML templates
 * Usage: node generate-preview.js <html-file> <output-image>
 */

console.log('Script started');

const { chromium } = require('@playwright/test');
const fs = require('fs');
const path = require('path');

console.log('Required modules loaded');

async function generatePreview(htmlPath, outputPath) {
    let browser;
    
    try {
        console.log('🚀 Launching browser...');
        // Launch headless browser
        browser = await chromium.launch({
            headless: true,
            args: [
                '--no-sandbox',
                '--disable-setuid-sandbox',
                '--disable-dev-shm-usage',
                '--disable-gpu'
            ]
        });
        console.log('✅ Browser launched successfully');

        const page = await browser.newPage();
        console.log('📄 Created new page');
        
        // Set viewport size - 640x400 at 2x for retina quality
        await page.setViewportSize({
            width: 640,
            height: 400
        });
        console.log('📐 Set viewport size');

        // Read and load HTML content
        const htmlContent = fs.readFileSync(htmlPath, 'utf8');
        console.log('📄 Loading HTML content...');
        await page.setContent(htmlContent, {
            waitUntil: 'domcontentloaded',
            timeout: 5000
        });
        console.log('✅ HTML content loaded');

        // Wait a bit for rendering
        await new Promise(resolve => setTimeout(resolve, 500));
        console.log('⏳ Waited for rendering');
        console.log('📸 Taking screenshot...');

        // Take full page screenshot
        await page.screenshot({
            path: outputPath,
            type: 'png',
            fullPage: false
        });

        console.log(`✅ Preview generated: ${path.basename(outputPath)}`);
        process.exit(0);

    } catch (error) {
        console.error(`❌ Error generating preview: ${error.message}`);
        console.error('Stack:', error.stack);
        process.exit(1);
    } finally {
        if (browser) {
            console.log('🔒 Closing browser...');
            await browser.close();
        }
    }
}

// Get command line arguments
const args = process.argv.slice(2);

if (args.length < 2) {
    console.error('Usage: node generate-preview.js <html-file> <output-image>');
    process.exit(1);
}

const [htmlPath, outputPath] = args;

// Validate input file exists
if (!fs.existsSync(htmlPath)) {
    console.error(`Error: HTML file not found: ${htmlPath}`);
    process.exit(1);
}

// Ensure output directory exists
const outputDir = path.dirname(outputPath);
if (!fs.existsSync(outputDir)) {
    fs.mkdirSync(outputDir, { recursive: true });
}

console.log('Calling generatePreview...');
// Generate the preview
generatePreview(htmlPath, outputPath);
