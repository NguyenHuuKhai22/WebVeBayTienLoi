<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class PrepareTrainingData extends Command
{
    protected $signature = 'chatbot:prepare-data';
    protected $description = 'Prepare training data for OpenAI from a text file';

    public function handle()
    {
        $this->info('Reading training data...');

        $content = Storage::get('training_data.txt');
        $lines = explode("\n", $content);

        $qa_pairs = [];
        $current_q = null;
        $current_a = null;

        foreach ($lines as $line) {
            $line = trim($line);

            if (empty($line)) {
                if ($current_q && $current_a) {
                    $qa_pairs[] = [
                        'question' => $current_q,
                        'answer' => $current_a
                    ];
                    $current_q = null;
                    $current_a = null;
                }
                continue;
            }

            if (strpos($line, 'Q: ') === 0) {
                $current_q = substr($line, 3);
            } elseif (strpos($line, 'A: ') === 0) {
                $current_a = substr($line, 3);
            } elseif ($current_a !== null) {
                $current_a .= ' ' . $line;
            } elseif ($current_q !== null) {
                $current_q .= ' ' . $line;
            }
        }

        // Add final pair
        if ($current_q && $current_a) {
            $qa_pairs[] = [
                'question' => $current_q,
                'answer' => $current_a
            ];
        }

        // Format for OpenAI
        $formatted_data = [];
        foreach ($qa_pairs as $pair) {
            $formatted_data[] = [
                'messages' => [
                    ['role' => 'system', 'content' => 'Bạn là NEO, trợ lý ảo của Vietnam Airlines.'],
                    ['role' => 'user', 'content' => $pair['question']],
                    ['role' => 'assistant', 'content' => $pair['answer']]
                ]
            ];
        }

        // Save as JSONL
        $jsonl = '';
        foreach ($formatted_data as $item) {
            $jsonl .= json_encode($item, JSON_UNESCAPED_UNICODE) . "\n";
        }

        Storage::put('training_data.jsonl', $jsonl);

        $this->info('Training data prepared successfully! Total Q&A pairs: ' . count($qa_pairs));
        $this->info('File saved to: ' . storage_path('app/training_data.jsonl'));
        $this->info('');
        $this->info('To use this file with OpenAI fine-tuning:');
        $this->info('1. Install OpenAI CLI: pip install openai');
        $this->info('2. Upload file: openai api files.create -f ' . storage_path('app/training_data.jsonl') . ' -p fine-tune');
        $this->info('3. Create fine-tuning job: openai api fine_tunes.create -t <FILE_ID> -m gpt-3.5-turbo');
    }
}
