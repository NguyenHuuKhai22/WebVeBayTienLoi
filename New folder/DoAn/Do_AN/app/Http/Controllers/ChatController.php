<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use OpenAI;

class ChatController extends Controller
{
    protected $client;
    protected $examples = [];

    public function __construct()
    {
        $this->client = OpenAI::client(env('OPENAI_API_KEY'));
        $this->loadExamples();
    }

    private function loadExamples()
    {
        if (Storage::exists('training_data.txt')) {
            $content = Storage::get('training_data.txt');
            $lines = explode("\n", $content);

            $qa_pairs = [];
            $current_q = null;
            $current_a = null;

            foreach ($lines as $line) {
                $line = trim($line);

                if (empty($line)) {
                    if ($current_q && $current_a) {
                        $this->examples[] = [
                            'role' => 'user',
                            'content' => $current_q
                        ];
                        $this->examples[] = [
                            'role' => 'assistant',
                            'content' => $current_a
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
                $this->examples[] = [
                    'role' => 'user',
                    'content' => $current_q
                ];
                $this->examples[] = [
                    'role' => 'assistant',
                    'content' => $current_a
                ];
            }
        }
    }

    public function chat(Request $request)
    {
        try {
            $userMessage = $request->input('message');

            // Xây dựng messages với ví dụ và tin nhắn người dùng
            $messages = [
                ['role' => 'system', 'content' => 'Bạn là NEO, trợ lý ảo của Vietnam Airlines. Hãy trả lời các câu hỏi về hãng hàng không, chuyến bay, đặt vé, và các dịch vụ của Vietnam Airlines một cách ngắn gọn, chính xác và thân thiện. Luôn trả lời bằng tiếng Việt.']
            ];

            // Thêm ví dụ (giới hạn số lượng để tránh token quá lớn)
            $exampleCount = min(count($this->examples), 6); // Lấy tối đa 3 cặp Q&A
            for ($i = 0; $i < $exampleCount; $i++) {
                $messages[] = $this->examples[$i];
            }

            // Thêm tin nhắn người dùng
            $messages[] = ['role' => 'user', 'content' => $userMessage];

            // Gọi OpenAI API
            $response = $this->client->chat()->create([
                'model' => 'gpt-3.5-turbo',
                'messages' => $messages,
                'temperature' => 0.7,
                'max_tokens' => 300,
            ]);

            return response()->json([
                'success' => true,
                'message' => $response->choices[0]->message->content
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Lỗi khi gọi API: ' . $e->getMessage()
            ], 500);
        }
    }
}
