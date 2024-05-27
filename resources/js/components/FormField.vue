<template>
  <DefaultField
    :field="field"
    :errors="errors"
    :show-help-text="showHelpText"
    :full-width-content="fullWidthContent"
  >
    <template #field>
      <div class="flex items-center" dusk="split-date-input">
        <SelectControl
          v-model:selected="year"
          :options="years"
          dusk="birth-year-select"
          @change="year = $event"
          :hasError="hasError"
          class="w-full"
        />
        <span class="px-3">{{ field.delimiters.year || '-' }}</span>
        <SelectControl
          v-model:selected="month"
          :options="months"
          dusk="birth-month-select"
          @change="month = $event"
          :hasError="hasError"
          class="w-full"
        />
        <span class="px-3">{{ field.delimiters.month || '-' }}</span>
        <SelectControl
          v-model:selected="day"
          :options="daysInMonth"
          dusk="birth-day-select"
          @change="day = $event"
          :hasError="hasError"
          class="w-full"
        />
        <span v-if="field.delimiters.day" class="pl-3">{{ field.delimiters.day || '' }}</span>
      </div>
    </template>
  </DefaultField>
</template>

<script>
import { DateTime } from 'luxon'
import {FormField, HandlesValidationErrors} from 'laravel-nova'

export default {
  mixins: [FormField, HandlesValidationErrors],

  props: ['resourceName', 'resourceId', 'field'],

  data() {
    return {
      year: '',
      month: '',
      day: '',
      years: this.getYears(),
      months: this.getMonths(),
    }
  },

  computed: {
    fullDate() {
      return DateTime.local(Number(this.year), Number(this.month), Number(this.day)).toISODate()
    },
    hasError() {
      return this.errors.length > 0
    },
    daysInMonth() {
      return this.getDays()
    }
  },

  watch: {
    fullDate(newVal) {
      this.value = newVal
    },
  },

  methods: {
    /*
     * Set the initial, internal value for the field.
     */
    setInitialValue() {
      const value = this.field.value || ''

      if (value) {
        let isoDate = DateTime.fromISO(value)
        this.year = isoDate.year
        this.month = isoDate.month
        this.day = isoDate.day
      }
    },

    /**
     * Fill the given FormData object with the field's internal value.
     */
    fill(formData) {
      formData.append(this.fieldAttribute, this.value || '')
    },

    getYears() {
      const year = this.field.limitYears.max
      const period = this.field.limitYears.max - this.field.limitYears.min + 1

      return Array.from({ length: period }, (v, k) => {
        return {
          value: year - k,
          label: year - k
        }
      })
    },

    getMonths() {
      const months = [
        { value: 1, label: this.__('January') },
        { value: 2, label: this.__('February') },
        { value: 3, label: this.__('March') },
        { value: 4, label: this.__('April') },
        { value: 5, label: this.__('May') },
        { value: 6, label: this.__('June') },
        { value: 7, label: this.__('July') },
        { value: 8, label: this.__('August') },
        { value: 9, label: this.__('September') },
        { value: 10, label: this.__('October') },
        { value: 11, label: this.__('November') },
        { value: 12, label: this.__('December') },
      ]

      if (!['-', '/'].includes(this.field.delimiters.month)) {
        months.map(item => item.label = this.formatNumber(item.value))
      }

      return months
    },

    getDays() {
      const date = (this.year && this.month) ? DateTime.local(Number(this.year), Number(this.month)) : DateTime.local()

      return Array.from({
        length: date.daysInMonth
      }, (v, k) => {
        return {
          value: k + 1,
          label: this.formatNumber(k + 1),
        }
      })
    },

    formatNumber(value) {
      return value.toString().padStart(2, '0');
    },
  },
}
</script>
