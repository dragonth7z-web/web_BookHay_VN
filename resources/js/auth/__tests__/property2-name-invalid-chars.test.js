/**
 * Feature: register-form-advanced-validation, Property 2: Họ tên chứa số hoặc ký tự đặc biệt bị từ chối
 * Validates: Requirements 1.3, 1.4
 */
import { describe, it } from 'vitest';
import fc from 'fast-check';
import { RegisterValidator } from '../auth.js';

describe('Feature: register-form-advanced-validation, Property 2: Họ tên chứa số hoặc ký tự đặc biệt bị từ chối', () => {
  const validator = new RegisterValidator('nonexistent');

  it('validateName rejects name with injected digit', () => {
    fc.assert(
      fc.property(
        fc.stringMatching(/^[a-zA-Z ]{3,}$/),
        fc.integer({ min: 0, max: 9 }),
        (name, digit) => {
          const result = validator.validateName(name + digit.toString());
          return result.valid === false;
        }
      ),
      { numRuns: 100 }
    );
  });

  it('validateName rejects name with injected special character', () => {
    fc.assert(
      fc.property(
        fc.stringMatching(/^[a-zA-Z ]{3,}$/),
        fc.constantFrom('!', '@', '#', '$', '%', '^', '&', '*', '(', ')', '-', '_', '+', '=', '[', ']', '{', '}', '|', ';', ':', "'", '"', ',', '.', '<', '>', '/', '?', '\\', '`', '~'),
        (name, special) => {
          const result = validator.validateName(name + special);
          return result.valid === false;
        }
      ),
      { numRuns: 100 }
    );
  });
});
