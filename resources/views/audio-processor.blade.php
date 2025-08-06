<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Audio Waveform Processor</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
        }
        .upload-area {
            border: 2px dashed #ccc;
            border-radius: 10px;
            padding: 40px;
            text-align: center;
            margin: 20px 0;
            cursor: pointer;
            transition: border-color 0.3s;
        }
        .upload-area:hover {
            border-color: #007bff;
        }
        .upload-area.dragover {
            border-color: #007bff;
            background-color: #f8f9fa;
        }
        .btn {
            background-color: #007bff;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            margin: 10px;
        }
        .btn:hover {
            background-color: #0056b3;
        }
        .btn:disabled {
            background-color: #6c757d;
            cursor: not-allowed;
        }
        .result {
            background: #f8f9fa;
            border: 1px solid #dee2e6;
            border-radius: 5px;
            padding: 15px;
            margin: 20px 0;
            font-family: monospace;
            white-space: pre-wrap;
            max-height: 400px;
            overflow-y: auto;
        }
        .error {
            color: #dc3545;
            background-color: #f8d7da;
            border: 1px solid #f5c6cb;
            border-radius: 5px;
            padding: 15px;
            margin: 20px 0;
        }
        .success {
            color: #155724;
            background-color: #d4edda;
            border: 1px solid #c3e6cb;
            border-radius: 5px;
            padding: 15px;
            margin: 20px 0;
        }
        .loading {
            display: none;
            text-align: center;
            margin: 20px 0;
        }
        .progress {
            width: 100%;
            height: 20px;
            background-color: #f0f0f0;
            border-radius: 10px;
            overflow: hidden;
            margin: 10px 0;
        }
        .progress-bar {
            height: 100%;
            background-color: #007bff;
            width: 0%;
            transition: width 0.3s;
        }
    </style>
</head>
<body>
    <h1>Audio Waveform Generator</h1>
    
    <div class="upload-area" onclick="document.getElementById('audioFile').click()">
        <p>Click here or drag and drop your audio file</p>
        <p><small>Supports MP3, WAV, M4A, OGG files</small></p>
    </div>
    
    <input type="file" id="audioFile" accept=".mp3,.wav,.m4a,.ogg,audio/*" style="display: none;">
    
    <div>
        <button class="btn" onclick="processAudio()" id="processBtn">Generate Waveform</button>
        <button class="btn" onclick="clearResults()" id="clearBtn">Clear Results</button>
    </div>
    
    <div class="loading" id="loading">
        <p>Processing audio file...</p>
        <div class="progress">
            <div class="progress-bar" id="progressBar"></div>
        </div>
    </div>
    
    <div id="result"></div>

    <script>
        let audioContext;
        let selectedFile;

        // Initialize audio context on user interaction
        document.addEventListener('click', initAudioContext, { once: true });
        document.addEventListener('touchstart', initAudioContext, { once: true });

        function initAudioContext() {
            if (!audioContext) {
                try {
                    audioContext = new (window.AudioContext || window.webkitAudioContext)();
                    console.log('Audio context initialized');
                } catch (e) {
                    showError('Web Audio API is not supported in this browser');
                }
            }
        }

        // File input handling
        document.getElementById('audioFile').addEventListener('change', function(e) {
            selectedFile = e.target.files[0];
            if (selectedFile) {
                showSuccess(`Selected: ${selectedFile.name} (${(selectedFile.size / 1024 / 1024).toFixed(2)} MB)`);
                validateFile(selectedFile);
            }
        });

        // Drag and drop handling
        const uploadArea = document.querySelector('.upload-area');
        
        uploadArea.addEventListener('dragover', function(e) {
            e.preventDefault();
            uploadArea.classList.add('dragover');
        });
        
        uploadArea.addEventListener('dragleave', function(e) {
            e.preventDefault();
            uploadArea.classList.remove('dragover');
        });
        
        uploadArea.addEventListener('drop', function(e) {
            e.preventDefault();
            uploadArea.classList.remove('dragover');
            
            const files = e.dataTransfer.files;
            if (files.length > 0) {
                selectedFile = files[0];
                document.getElementById('audioFile').files = files;
                showSuccess(`Selected: ${selectedFile.name} (${(selectedFile.size / 1024 / 1024).toFixed(2)} MB)`);
                validateFile(selectedFile);
            }
        });

        function validateFile(file) {
            const validTypes = ['audio/mpeg', 'audio/wav', 'audio/mp3', 'audio/ogg', 'audio/m4a', 'audio/mp4'];
            const validExtensions = ['.mp3', '.wav', '.m4a', '.ogg'];
            
            const fileExtension = file.name.toLowerCase().substring(file.name.lastIndexOf('.'));
            const isValidType = validTypes.includes(file.type) || validExtensions.includes(fileExtension);
            
            if (!isValidType) {
                showError('Please select a valid audio file (MP3, WAV, M4A, OGG)');
                selectedFile = null;
                return false;
            }
            
            if (file.size > 50 * 1024 * 1024) { // 50MB limit
                showError('File size must be less than 50MB');
                selectedFile = null;
                return false;
            }
            
            return true;
        }

        async function processAudio() {
            if (!selectedFile) {
                showError('Please select an audio file first');
                return;
            }

            if (!audioContext) {
                initAudioContext();
                if (!audioContext) {
                    return;
                }
            }

            // Resume audio context if suspended
            if (audioContext.state === 'suspended') {
                await audioContext.resume();
            }

            showLoading(true);
            updateProgress(10);

            try {
                console.log('Starting audio processing...');
                
                // Read file as array buffer
                updateProgress(20);
                const arrayBuffer = await readFileAsArrayBuffer(selectedFile);
                console.log('File read as array buffer, size:', arrayBuffer.byteLength);
                
                updateProgress(40);
                
                // Decode audio data
                console.log('Decoding audio data...');
                const audioBuffer = await audioContext.decodeAudioData(arrayBuffer.slice());
                console.log('Audio decoded successfully:', {
                    duration: audioBuffer.duration,
                    sampleRate: audioBuffer.sampleRate,
                    channels: audioBuffer.numberOfChannels,
                    length: audioBuffer.length
                });
                
                updateProgress(70);
                
                // Generate waveform data
                console.log('Generating waveform...');
                const waveformData = generateWaveform(audioBuffer);
                console.log('Waveform generated:', waveformData);
                
                updateProgress(90);
                
                // Send to server (optional)
                const response = await sendToServer(waveformData);
                
                updateProgress(100);
                showLoading(false);
                
                // Display results
                showResult(response || waveformData);
                
            } catch (error) {
                console.error('Detailed error:', error);
                showLoading(false);
                
                let errorMessage = 'Error processing audio file: ';
                
                if (error.name === 'EncodingError' || error.message.includes('decode')) {
                    errorMessage += 'Unable to decode audio file. The file may be corrupted or in an unsupported format.';
                } else if (error.name === 'NotSupportedError') {
                    errorMessage += 'This audio format is not supported by your browser.';
                } else if (error.message.includes('network') || error.message.includes('fetch')) {
                    errorMessage += 'Network error. Please check your connection.';
                } else {
                    errorMessage += error.message || 'Unknown error occurred.';
                }
                
                showError(errorMessage);
            }
        }

        function readFileAsArrayBuffer(file) {
            return new Promise((resolve, reject) => {
                const reader = new FileReader();
                reader.onload = () => resolve(reader.result);
                reader.onerror = () => reject(new Error('Failed to read file'));
                reader.readAsArrayBuffer(file);
            });
        }

        function generateWaveform(audioBuffer) {
            const sampleRate = audioBuffer.sampleRate;
            const channels = audioBuffer.numberOfChannels;
            const samplesPerPixel = 4766;
            
            console.log('Processing audio buffer:', {
                sampleRate,
                channels,
                duration: audioBuffer.duration,
                totalSamples: audioBuffer.length
            });
            
            // Get audio data (use first channel, or mix down if stereo)
            let audioData;
            if (channels === 1) {
                audioData = audioBuffer.getChannelData(0);
            } else {
                // Mix stereo to mono
                const left = audioBuffer.getChannelData(0);
                const right = audioBuffer.getChannelData(1);
                audioData = new Float32Array(left.length);
                for (let i = 0; i < left.length; i++) {
                    audioData[i] = (left[i] + right[i]) / 2;
                }
            }
            
            const totalSamples = audioData.length;
            const waveformData = [];
            
            // Process in chunks
            for (let i = 0; i < totalSamples; i += samplesPerPixel) {
                const chunkSize = Math.min(samplesPerPixel, totalSamples - i);
                
                let minValue = 0;
                let maxValue = 0;
                
                // Find min/max in chunk
                for (let j = 0; j < chunkSize; j++) {
                    // Convert float (-1 to 1) to 8-bit signed (-128 to 127)
                    const sample = Math.round(Math.max(-1, Math.min(1, audioData[i + j])) * 127);
                    minValue = Math.min(minValue, sample);
                    maxValue = Math.max(maxValue, sample);
                }
                
                waveformData.push(minValue);
                waveformData.push(maxValue);
            }
            
            // Also prepare raw PCM data (downsample if too large)
            let pcmData = Array.from(audioData);
            const maxPcmSamples = 50000; // Limit for performance
            if (pcmData.length > maxPcmSamples) {
                const step = Math.ceil(pcmData.length / maxPcmSamples);
                pcmData = pcmData.filter((_, idx) => idx % step === 0);
            }
            
            // Limit data size for performance
            if (waveformData.length > 10000) {
                console.log('Truncating waveform data from', waveformData.length, 'to 10000');
                waveformData.splice(10000);
            }
            
            return {
                version: 2,
                channels: 1, // We convert to mono
                sample_rate: sampleRate,
                samples_per_pixel: samplesPerPixel,
                bits: 8,
                length: Math.floor(waveformData.length / 2),
                data: waveformData,
                pcm_data: pcmData // Add raw PCM data
            };
        }

        async function sendToServer(waveformData) {
            // Optional: Send to Laravel backend
            try {
                const csrfToken = document.querySelector('meta[name="csrf-token"]');
                if (!csrfToken) {
                    console.log('No CSRF token found, skipping server request');
                    return null;
                }

                const response = await fetch('/api/audio/process-waveform', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken.content
                    },
                    body: JSON.stringify(waveformData)
                });
                
                if (!response.ok) {
                    throw new Error(`Server error: ${response.status}`);
                }
                
                return await response.json();
            } catch (error) {
                console.log('Server request failed:', error.message);
                return null; // Fail gracefully, return client-side data
            }
        }

        function showLoading(show) {
            document.getElementById('loading').style.display = show ? 'block' : 'none';
            document.getElementById('processBtn').disabled = show;
        }

        function updateProgress(percent) {
            document.getElementById('progressBar').style.width = percent + '%';
        }

        function showResult(data) {
            const resultDiv = document.getElementById('result');
            resultDiv.innerHTML = '<div class="success">Waveform generated successfully!</div>';
            resultDiv.innerHTML += '<div class="result" id="waveformJson">' + JSON.stringify(data, null, 2) + '</div>';
            resultDiv.innerHTML += '<button class="btn" id="copyBtn" style="margin-top:10px;">Copy to Clipboard</button>';
            // Add event listener for copy button
            document.getElementById('copyBtn').onclick = function() {
                copyWaveformJson();
            };
        }

        function copyWaveformJson() {
            const jsonDiv = document.getElementById('waveformJson');
            if (!jsonDiv) return;
            const text = jsonDiv.textContent;
            navigator.clipboard.writeText(text).then(function() {
                showCopySuccess();
            }, function() {
                showError('Failed to copy to clipboard.');
            });
        }

        function showCopySuccess() {
            const resultDiv = document.getElementById('result');
            const msg = document.createElement('div');
            msg.className = 'success';
            msg.textContent = 'Copied to clipboard!';
            resultDiv.insertBefore(msg, resultDiv.firstChild);
            setTimeout(() => {
                if (msg.parentNode) msg.parentNode.removeChild(msg);
            }, 1500);
        }

        function showError(message) {
            const resultDiv = document.getElementById('result');
            resultDiv.innerHTML = '<div class="error">' + message + '</div>';
        }

        function showSuccess(message) {
            const resultDiv = document.getElementById('result');
            resultDiv.innerHTML = '<div class="success">' + message + '</div>';
        }

        function clearResults() {
            document.getElementById('result').innerHTML = '';
            document.getElementById('audioFile').value = '';
            selectedFile = null;
            updateProgress(0);
        }

        // Test audio context support on page load
        window.addEventListener('load', function() {
            if (!window.AudioContext && !window.webkitAudioContext) {
                showError('Web Audio API is not supported in this browser. Please use Chrome, Firefox, Safari, or Edge.');
            }
        });
    </script>
</body>
</html>