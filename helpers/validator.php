<?php
declare(strict_types=1);

class Validator {
    private array $errors = [];
    private array $data;

    public function __construct(array $data) {
        $this->data = $data;
    }

    public function required(string $field, string $label = ''): static {
        $label = $label ?: ucfirst(str_replace('_', ' ', $field));
        if (empty(trim((string)($this->data[$field] ?? '')))) {
            $this->errors[$field] = "{$label} is required.";
        }
        return $this;
    }

    public function email(string $field): static {
        if (!empty($this->data[$field]) && !filter_var($this->data[$field], FILTER_VALIDATE_EMAIL)) {
            $this->errors[$field] = 'Invalid email address.';
        }
        return $this;
    }

    public function min(string $field, int $min): static {
        $val = $this->data[$field] ?? '';
        if (strlen((string)$val) < $min) {
            $this->errors[$field] = "Must be at least {$min} characters.";
        }
        return $this;
    }

    public function max(string $field, int $max): static {
        $val = $this->data[$field] ?? '';
        if (strlen((string)$val) > $max) {
            $this->errors[$field] = "Must be at most {$max} characters.";
        }
        return $this;
    }

    public function numeric(string $field): static {
        if (!empty($this->data[$field]) && !is_numeric($this->data[$field])) {
            $this->errors[$field] = 'Must be a number.';
        }
        return $this;
    }

    public function integer(string $field): static {
        if (!empty($this->data[$field]) && !filter_var($this->data[$field], FILTER_VALIDATE_INT)) {
            $this->errors[$field] = 'Must be an integer.';
        }
        return $this;
    }

    public function in(string $field, array $allowed): static {
        if (!empty($this->data[$field]) && !in_array($this->data[$field], $allowed, true)) {
            $this->errors[$field] = 'Invalid value.';
        }
        return $this;
    }

    public function fails(): bool { return !empty($this->errors); }
    public function passes(): bool { return empty($this->errors); }
    public function errors(): array { return $this->errors; }

    public static function make(array $data): static {
        return new static($data);
    }
}
