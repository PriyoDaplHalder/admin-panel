<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;

class AudioController extends Controller
{
    public function processWaveform(Request $request): JsonResponse
    {
        try {
            // Validate the waveform data from client
            $validated = $request->validate([
                'version' => 'required|integer',
                'channels' => 'required|integer|min:1|max:2',
                'sample_rate' => 'required|integer|min:8000|max:192000',
                'samples_per_pixel' => 'required|integer|min:1',
                'bits' => 'required|integer|in:8,16,24,32',
                'length' => 'required|integer|min:0',
                'data' => 'required|array|max:10000',
                // Accept optional raw PCM data array (float or int)
                'pcm_data' => 'nullable|array',
                'pcm_data.*' => 'numeric' // Accept float or int values
            ]);
            $processedData = $this->processWaveformData($validated);

            return response()->json([
                'success' => true,
                'message' => 'Waveform processed successfully',
                'original_data' => $validated,
                'processed_data' => $processedData,
                'stats' => [
                    'duration_seconds' => round($validated['length'] * $validated['samples_per_pixel'] / $validated['sample_rate'], 2),
                    'data_points' => count($validated['data']),
                    'peak_amplitude' => $this->getPeakAmplitude($validated['data']),
                    // Add PCM stats if present
                    'pcm_data_points' => isset($validated['pcm_data']) ? count($validated['pcm_data']) : null,
                    'pcm_peak_amplitude' => isset($validated['pcm_data']) ? $this->getPeakAmplitude($validated['pcm_data']) : null
                ]
            ]);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'error' => 'Invalid waveform data',
                'details' => $e->errors()
            ], 422);

        } catch (\Exception $e) {
            Log::error('Waveform processing error: ' . $e->getMessage());
            
            return response()->json([
                'error' => 'Server error processing waveform',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    private function processWaveformData(array $waveformData): array
    {
        // Apply server-side processing if needed
        $data = $waveformData['data'];
        
        // Example: Normalize amplitude
        $maxAmp = max(array_map('abs', $data));
        if ($maxAmp > 0) {
            $normalizedData = array_map(function($value) use ($maxAmp) {
                return round(($value / $maxAmp) * 127);
            }, $data);
        } else {
            $normalizedData = $data;
        }

        return [
            'version' => $waveformData['version'],
            'channels' => $waveformData['channels'],
            'sample_rate' => $waveformData['sample_rate'],
            'samples_per_pixel' => $waveformData['samples_per_pixel'],
            'bits' => $waveformData['bits'],
            'length' => $waveformData['length'],
            'data' => $normalizedData,
            'normalized' => true,
            'peak_original' => $maxAmp
        ];
    }

    private function getPeakAmplitude(array $data): int
    {
        return max(array_map('abs', $data));
    }
}