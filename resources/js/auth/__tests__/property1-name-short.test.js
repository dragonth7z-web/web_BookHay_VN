/**
 * Feature: register-form-advanced-validation, Property 1: Họ tên ngắn bị từ chối
 * Validates: Requirements 1.2
 */
import { describe, it } from 'vitest';
import fc from 'fast-check';
import { RegisterValidator } from '../auth.js';

describe('Feature: register-form-advanced-validation, Property 1: Họ tên ngắn bị từ chối', () => {
  it('validateName rejects any string of length 1-2', () => {
    const validator = new RegisterValidator('nonexistent');
    fc.assert(
      fc.property(
        fc.string({ minLength: 1, maxLength: 2 }),
        (name) => {
          const result = validator.validateName(name);
          return result.valid === false;
        }
      ),
      { numRuns: 100 }
    );
  });
});
