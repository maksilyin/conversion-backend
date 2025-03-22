<?php
namespace App\Queue\Jobs;
use App\Jobs\ProcessServiceResponseJob;
use Illuminate\Contracts\Container\BindingResolutionException;
use VladimirYuldashev\LaravelQueueRabbitMQ\Queue\Jobs\RabbitMQJob as BaseJob;
use function Laravel\Prompts\error;

class CustomRabbitMQJob extends BaseJob
{
    /**
     * @throws BindingResolutionException
     * @throws \Exception
     */
    public function fire(): void
    {
        $payload = $this->payload();

        if (isset($payload['service'])) {
            $requiredKeys = ['task', 'type', 'result'];

            foreach ($requiredKeys as $key) {
                if (!array_key_exists($key, $payload)) {
                    $this->delete();
                    throw new \Exception("Key '{$key}' is missing in the payload. Service: " . $payload['service']);
                }
            }

            $class = ProcessServiceResponseJob::class;
            $method = 'handle';

            $data = [
                'taskId' => $payload['task'],
                'payload' => $payload,
            ];

            ($this->instance = app()->make($class, $data))->{$method}($this);

            $this->delete();
        }

        else {
            parent::fire();
        }
    }

    public function getName(): string
    {
        return '';
    }
}
