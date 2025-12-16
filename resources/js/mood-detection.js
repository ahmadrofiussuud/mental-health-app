document.addEventListener('DOMContentLoaded', () => {
    // --- Elements ---
    const videoElement = document.getElementById('camera-feed');
    const canvasElement = document.getElementById('camera-canvas');
    const cameraPlaceholder = document.getElementById('camera-placeholder');
    const startCameraBtn = document.getElementById('start-camera-btn');
    const startScanBtn = document.getElementById('start-scan-btn');
    const scanOverlay = document.getElementById('scan-overlay');
    const scanTimerElement = document.getElementById('scan-timer');

    // Result Elements
    const resultCard = document.getElementById('result-card');
    const moodResultDisplay = document.getElementById('mood-result-display');
    const moodResultPlaceholder = document.getElementById('mood-result-placeholder');
    const moodTitle = document.getElementById('mood-title');
    const moodDesc = document.getElementById('mood-desc');
    const moodBar = document.getElementById('mood-bar');
    const moodConfidence = document.getElementById('mood-confidence');

    // Voice Elements
    const recordBtn = document.getElementById('record-btn');
    const recordStatus = document.getElementById('record-status');
    const audioCanvas = document.getElementById('audio-visualizer');
    const transcriptArea = document.getElementById('transcript-area');
    const voiceConclusion = document.getElementById('voice-conclusion');
    const sentimentText = document.getElementById('sentiment-text');

    // --- State ---
    let isAIReady = false;
    let isScanning = false;
    let scanInterval;
    let detectionLoop;
    let collectedEmotions = [];

    // --- AI Setup ---
    const modelUrl = 'https://justadudewhohacks.github.io/face-api.js/models';

    // Check if faceapi is defined
    if (typeof faceapi === 'undefined') {
        console.error("Critical: face-api.js not loaded.");
        alert("Error: Library AI tidak termuat. Coba refresh halaman atau cek koneksi internet.");
    } else {
        Promise.all([
            faceapi.nets.tinyFaceDetector.loadFromUri(modelUrl),
            faceapi.nets.faceExpressionNet.loadFromUri(modelUrl)
        ]).then(() => {
            console.log("AI Models Loaded");
            isAIReady = true;
            // Enable button if model ready
            startCameraBtn.innerText = "Enable Camera (AI Ready)";
        }).catch(err => {
            console.error("Error loading AI models:", err);
            alert("Gagal memuat model AI. Pastikan terkoneksi internet.");
        });
    }

    // --- Camera Logic ---
    startCameraBtn.addEventListener('click', async () => {
        if (startCameraBtn.disabled) return;

        try {
            console.log("Requesting Camera Access...");
            const stream = await navigator.mediaDevices.getUserMedia({ video: true, audio: false }); // Audio false for camera only to prevent feedback
            videoElement.srcObject = stream;

            // UI Updates
            cameraPlaceholder.style.opacity = '0';
            setTimeout(() => { cameraPlaceholder.style.display = 'none'; }, 300); // Fade out

            startCameraBtn.style.display = 'none';
            startScanBtn.disabled = false;

            // setupAudioVisualizer(stream); // Separate audio stream for voice feature
            startPassiveDetection();

        } catch (err) {
            alert("Akses Kamera Ditolak / Error. Izinkan browser mengakses kamera.");
            console.error("Camera Error:", err);
        }
    });

    // --- Passive Detection ---
    function startPassiveDetection() {
        videoElement.addEventListener('play', () => {
            console.log("Video Playing");
            const displaySize = { width: videoElement.clientWidth, height: videoElement.clientHeight };
            canvasElement.width = displaySize.width;
            canvasElement.height = displaySize.height;
            faceapi.matchDimensions(canvasElement, displaySize);

            detectionLoop = setInterval(async () => {
                if (!isAIReady || videoElement.paused || videoElement.ended) return;

                try {
                    const detections = await faceapi.detectAllFaces(videoElement, new faceapi.TinyFaceDetectorOptions())
                        .withFaceExpressions();

                    const ctx = canvasElement.getContext('2d');
                    ctx.clearRect(0, 0, canvasElement.width, canvasElement.height);

                    if (detections.length > 0) {
                        const resizedDetections = faceapi.resizeResults(detections, displaySize);

                        // Custom Mirroring Fix
                        resizedDetections.forEach(detection => {
                            const box = detection.detection.box;
                            // Mirror X coordinate
                            const mirroredX = canvasElement.width - box.x - box.width;

                            // Draw Box
                            ctx.strokeStyle = '#00b5d8';
                            ctx.lineWidth = 3;
                            ctx.strokeRect(mirroredX, box.y, box.width, box.height);

                            // Draw Label
                            const sorted = detection.expressions.asSortedArray();
                            const top = sorted[0];
                            const label = `${top.expression} (${Math.round(top.probability * 100)}%)`;

                            ctx.fillStyle = '#00b5d8';
                            ctx.fillRect(mirroredX, box.y - 25, box.width, 25);

                            ctx.fillStyle = '#ffffff';
                            ctx.font = 'bold 14px sans-serif';
                            ctx.fillText(label, mirroredX + 5, box.y - 7);

                            if (isScanning) {
                                collectedEmotions.push(top);
                            }
                        });
                    }
                } catch (e) {
                    console.error("Detection Error:", e);
                }
            }, 200); // Throttled to 200ms for performance
        });
    }

    // --- Active Scanning Logic ---
    startScanBtn.addEventListener('click', () => {
        if (!isAIReady) return;

        isScanning = true;
        collectedEmotions = [];
        scanOverlay.classList.remove('hidden');
        startScanBtn.disabled = true;

        let timeLeft = 5;
        scanTimerElement.innerText = timeLeft;

        scanInterval = setInterval(() => {
            timeLeft--;
            scanTimerElement.innerText = timeLeft;

            if (timeLeft <= 0) {
                clearInterval(scanInterval);
                finishScanning();
            }
        }, 1000);
    });

    function finishScanning() {
        isScanning = false;
        scanOverlay.classList.add('hidden');
        startScanBtn.disabled = false;

        // Process Results
        if (collectedEmotions.length === 0) {
            alert("Wajah tidak terdeteksi selama scanning. Coba lagi.");
            return;
        }

        // Find most frequent emotion
        const emotionCounts = {};
        let maxEmotion = null;
        let maxCount = 0;

        collectedEmotions.forEach(e => {
            const expr = e.expression;
            emotionCounts[expr] = (emotionCounts[expr] || 0) + 1;
            if (emotionCounts[expr] > maxCount) {
                maxCount = emotionCounts[expr];
                maxEmotion = e;
            }
        });

        // Show Result UI
        displayResult(maxEmotion);
    }

    function displayResult(emotionData) {
        resultCard.style.opacity = '1';
        moodResultPlaceholder.style.display = 'none';
        moodResultDisplay.classList.remove('hidden');

        const expr = emotionData.expression;
        const confidence = Math.round(emotionData.probability * 100);

        moodTitle.innerText = expr.charAt(0).toUpperCase() + expr.slice(1);
        moodConfidence.innerText = confidence + "%";

        // Update Bar Width
        setTimeout(() => {
            moodBar.style.width = confidence + "%";
        }, 100);

        // Dynamic Description & Colors
        let desc = "";
        let colorClass = "from-blue-400 to-indigo-500";

        switch (expr) {
            case 'happy':
                desc = "Great to see you smiling! Keep up the positive vibes.";
                colorClass = "from-yellow-400 to-orange-500";
                break;
            case 'sad':
                desc = "It's okay to feel down. Take some time for self-care.";
                colorClass = "from-blue-400 to-blue-600";
                break;
            case 'angry':
                desc = "Take deep breaths. Try to channel this energy constructively.";
                colorClass = "from-red-500 to-red-600";
                break;
            case 'surprised':
                desc = "Whoa! Something unexpected happened?";
                colorClass = "from-purple-400 to-pink-500";
                break;
            case 'neutral':
                desc = "Feeling balanced and calm is a valid state of being.";
                colorClass = "from-teal-400 to-emerald-500";
                break;
            default:
                desc = "Your emotions are valid. We are here for you.";
        }

        moodDesc.innerText = desc;
        moodBar.className = `h-3 rounded-full transition-all duration-1000 bg-gradient-to-r ${colorClass}`;
    }

    // --- Voice Recording (Fixed) ---
    const SpeechRecognition = window.SpeechRecognition || window.webkitSpeechRecognition;
    let recognition;
    let isRecording = false;

    if (SpeechRecognition) {
        recognition = new SpeechRecognition();
        recognition.lang = 'id-ID';
        recognition.continuous = true;
        recognition.interimResults = true;

        recognition.onstart = () => {
            console.log("Mic Active");
            isRecording = true;
            updateRecordBtnState();
            transcriptArea.placeholder = "Mendengarkan...";
            if (transcriptArea.value.includes("Error") || transcriptArea.value.includes("System")) {
                transcriptArea.value = "";
            }
        };

        recognition.onresult = (event) => {
            let fullTranscript = '';
            for (let i = 0; i < event.results.length; i++) {
                fullTranscript += event.results[i][0].transcript;
            }
            if (fullTranscript.trim() !== "") {
                transcriptArea.value = fullTranscript;
                analyzeSentiment(fullTranscript);
            }
        };

        recognition.onerror = (event) => {
            console.error("Voice Error:", event.error);
            isRecording = false;
            updateRecordBtnState();

            let msg = "Error: ";
            if (event.error === 'no-speech') {
                console.warn("No speech detected, restarting...");
                return; // Ignore no-speech and let it remain active or user try again without hard error
            }

            switch (event.error) {
                case 'not-allowed': msg += "Izin mikrofon ditolak."; break;
                case 'network': msg += "Butuh koneksi internet."; break;
                default: msg += event.error;
            }
            transcriptArea.value = `[${msg}]`;
        };

        recognition.onend = () => {
            console.log("Mic Stopped");
            isRecording = false;
            updateRecordBtnState();
        };

    } else {
        console.warn("Browser not supporting SpeechRecognition");
        transcriptArea.value = "[Browser ini tidak mendukung fitur Suara-ke-Teks. Gunakan Chrome/Edge.]";
        recordBtn.disabled = true;
        recordBtn.classList.add('opacity-50', 'cursor-not-allowed');
    }

    recordBtn.addEventListener('click', () => {
        if (!recognition) return alert("Browser tidak support fitur ini.");

        if (isRecording) {
            recognition.stop();
        } else {
            // Request Mic Permission via getUserMedia first to ensure prompt appears
            navigator.mediaDevices.getUserMedia({ audio: true })
                .then(stream => {
                    // Success getting mic, now start recognition
                    setupAudioVisualizer(stream);
                    recognition.start();
                })
                .catch(err => {
                    alert("Gagal mengakses mikrofon: " + err.message);
                });
        }
    });

    function updateRecordBtnState() {
        if (isRecording) {
            recordBtn.classList.add('animate-pulse', 'bg-red-600', 'ring-4', 'ring-red-300');
            recordStatus.innerText = "Mendengarkan... (Klik untuk Stop)";
            recordStatus.className = "text-red-500 text-xs font-bold tracking-wide animate-pulse";
        } else {
            recordBtn.classList.remove('animate-pulse', 'bg-red-600', 'ring-4', 'ring-red-300');
            recordStatus.innerText = "TAP TO RECORD";
            recordStatus.className = "text-slate-400 text-xs font-medium tracking-wide";
        }
    }

    // --- Sentiment ---
    const positiveWords = ['senang', 'bahagia', 'gembira', 'sukses', 'bagus', 'terbaik', 'cinta', 'suka', 'semangat', 'bersyukur', 'keren', 'mantap', 'baik', 'alhamdulillah', 'puji', 'indah', 'nyaman', 'tenang'];
    const negativeWords = ['sedih', 'kecewa', 'marah', 'benci', 'sakit', 'buruk', 'gagal', 'lelah', 'capek', 'pusing', 'stress', 'takut', 'khawatir', 'bingung', 'kesal', 'bosan', 'hancur', 'mati', 'duka', 'rugi'];

    function analyzeSentiment(text) {
        if (!text) return;

        let score = 0;
        const words = text.toLowerCase().split(/\s+/);

        words.forEach(word => {
            if (positiveWords.includes(word)) score++;
            if (negativeWords.includes(word)) score--;
        });

        voiceConclusion.classList.remove('hidden');
        if (score > 0) {
            sentimentText.innerText = "Positif / Optimis ✨";
            sentimentText.className = "text-green-600 font-bold text-lg";
        } else if (score < 0) {
            sentimentText.innerText = "Negatif / Sedang Berat 🌧️";
            sentimentText.className = "text-purple-600 font-bold text-lg";
        } else {
            sentimentText.innerText = "Netral / Biasa Saja 😐";
            sentimentText.className = "text-gray-600 font-bold text-lg";
        }
    }

    // --- Audio Visualizer ---
    function setupAudioVisualizer(stream) {
        const context = new (window.AudioContext || window.webkitAudioContext)();
        const analyser = context.createAnalyser();
        const source = context.createMediaStreamSource(stream);
        source.connect(analyser);
        analyser.fftSize = 256;
        const dataArray = new Uint8Array(analyser.frequencyBinCount);

        const canvas = audioCanvas;
        const ctx = canvas.getContext('2d');

        function draw() {
            requestAnimationFrame(draw);
            analyser.getByteFrequencyData(dataArray);
            ctx.clearRect(0, 0, canvas.width, canvas.height);
            ctx.fillStyle = 'rgba(124, 58, 237, 0.5)';
            const barWidth = (canvas.width / dataArray.length) * 2.5;
            let x = 0;
            for (let i = 0; i < dataArray.length; i++) {
                ctx.fillRect(x, canvas.height - dataArray[i] / 2, barWidth, dataArray[i] / 2);
                x += barWidth + 1;
            }
        }
        draw();
    }
});
