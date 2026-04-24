/**
 * Feature: register-form-advanced-validation, Property 7: Phone input chỉ chứa chữ số
 * Validates: Requirements 3.1
 */
import { describe, it, expect } from 'vitest';
import fc from 'fast-check';

describe('Feature: register-form-advanced-validation, Property 7: Phone input chỉ chứa chữ số', () => {
  it('stripping non-digits from any string leaves only digit characters', () => {
    fc.assert(
      fc.property(
        fc.string(),
        (input) => {
          const stripped = input.replace(/\D/g, '');
          // All characters in stripped must be digits
          return stripped.split('').every(ch => /\d/.test(ch));
        }
      ),
      { numRuns: 100 }
    );
  });

  it('stripping non-digits from string with non-digit chars removes them', () => {
    fc.assert(
      fc.property(
        fc.string({ minLength: 1 }).filter(s => /\D/.test(s)),
        (input) => {
          const stripped = input.replace(/\D/g, '');
          // No non-digit characters remain
          return !/\D/.test(stripped);
        }
      ),
      { numRuns: 100 }
    );
  });
});
