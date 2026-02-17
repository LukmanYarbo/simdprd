class AnggotaWizard {
    constructor(config = {}) {
        this.config = Object.assign({
            formId: 'wizardForm',
            baseUrl: 'https://www.emsifa.com/api-wilayah-indonesia/api',
            validationUrl: '/admin/anggota/validate-step',
            currentData: {}
        }, config);

        this.form = document.getElementById(this.config.formId);
        if (!this.form) return;

        this.steps = this.form.querySelectorAll('.form-step');
        this.nextButtons = this.form.querySelectorAll('.next-btn');
        this.prevButtons = this.form.querySelectorAll('.prev-btn');
        this.stepperItems = document.querySelectorAll('.step-item');
        
        this.provSelect = document.getElementById('prov');
        this.kabSelect = document.getElementById('kab');
        this.kecSelect = document.getElementById('kec');
        this.desaSelect = document.getElementById('desa');
        this.fotoInput = document.getElementById('foto_anggota');
        this.previewImg = document.getElementById('preview');

        this.init();
    }

    async init() {
        this.initWizardNavigation();
        await this.initRegionalCascading();
        this.initImagePreview();
        this.initDynamicValidation();
        this.initInsuranceToggles();
        this.initFormSubmission();
    }

    initInsuranceToggles() {
        ['bpjs', 'jkk', 'jkm'].forEach(type => {
            const radios = document.querySelectorAll(`input[name="status_${type}"]`);
            const wrapper = document.getElementById(`no_${type}_wrapper`);
            const input = document.getElementById(`no_${type}`);

            if (radios.length && wrapper && input) {
                radios.forEach(radio => {
                    radio.addEventListener('change', () => {
                        if (radio.value === 'Y') {
                            wrapper.style.visibility = 'visible';
                            wrapper.style.pointerEvents = 'auto';
                            input.focus();
                        } else {
                            wrapper.style.visibility = 'hidden';
                            wrapper.style.pointerEvents = 'none';
                            input.value = ''; // Clear value if "No" is selected
                            this.clearValidation(input);
                        }
                    });
                });
            }
        });
    }

    initDynamicValidation() {
        const inputs = this.form.querySelectorAll('input:not([type="hidden"]), select, textarea');
        inputs.forEach((input, index) => {
            const eventType = input.tagName === 'SELECT' || input.type === 'file' ? 'change' : 'blur';
            
            input.addEventListener(eventType, async () => {
                // Skip if empty and not required
                if (!input.value && !input.hasAttribute('required')) {
                    this.clearValidation(input);
                    return;
                }

                const isValid = await this.validateFieldServer(input);
                if (isValid) {
                    this.markValid(input);
                    // Autofocus next input if exists in the same step
                    this.focusNextInput(input, inputs, index);
                }
            });
        });
    }

    async validateFieldServer(input) {
        const stepElement = input.closest('.form-step');
        if (!stepElement) return false;
        
        const stepNumber = parseInt(stepElement.id.replace('step', ''));
        const formData = new FormData();
        formData.append('step', stepNumber);
        formData.append(input.name, input.value);
        if (this.config.currentData.id) {
            formData.append('id', this.config.currentData.id);
        }

        const csrfToken = document.querySelector('meta[name="csrf-token"]').content;

        try {
            const response = await fetch(this.config.validationUrl, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json'
                },
                body: formData
            });

            const result = await response.json();

            if (response.ok) {
                return true;
            } else {
                // Only mark invalid if THIS specific field has an error
                if (result.errors && result.errors[input.name]) {
                    this.markInvalid(input, result.errors[input.name][0]);
                    return false;
                }
                // If this field doesn't have an error but the request failed (e.g. other fields missing), 
                // we treat this field as "possibly okay" or at least not "invalid" yet.
                // However, for UX, if it's required and empty, it should be invalid.
                if (input.hasAttribute('required') && !input.value) {
                    this.markInvalid(input, 'Field ini wajib diisi');
                    return false;
                }
                
                return true; // No specific error for this field
            }
        } catch (error) {
            console.error('Field validation error:', error);
            return false;
        }
    }

    markValid(input) {
        input.classList.remove('is-invalid');
        input.classList.add('is-valid');
        const feedback = input.parentNode.querySelector('.invalid-feedback');
        if (feedback) feedback.remove();
    }

    markInvalid(input, message) {
        input.classList.remove('is-valid');
        input.classList.add('is-invalid');
        let feedback = input.parentNode.querySelector('.invalid-feedback');
        if (!feedback) {
            feedback = document.createElement('div');
            feedback.className = 'invalid-feedback';
            input.parentNode.appendChild(feedback);
        }
        feedback.textContent = message;
    }

    clearValidation(input) {
        input.classList.remove('is-valid', 'is-invalid');
        const feedback = input.parentNode.querySelector('.invalid-feedback');
        if (feedback) feedback.remove();
    }

    focusNextInput(currentInput, allInputs, currentIndex) {
        const currentStep = currentInput.closest('.form-step');
        // Find next input in the same step
        for (let i = currentIndex + 1; i < allInputs.length; i++) {
            const nextInput = allInputs[i];
            if (nextInput.closest('.form-step') === currentStep && !nextInput.disabled && nextInput.type !== 'hidden') {
                nextInput.focus();
                break;
            }
        }
    }

    initFormSubmission() {
        this.form.addEventListener('submit', async (e) => {
            e.preventDefault();
            
            // Show loading overlay if you have one, or just disable button
            const submitBtn = this.form.querySelector('button[type="submit"]');
            const originalText = submitBtn.innerHTML;
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span> Processing...';

            try {
                const isValid = await this.validateAllSteps();
                if (isValid) {
                    this.form.submit(); // Actually submit if all is good
                } else {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Data Belum Lengkap',
                        text: 'Masih ada data yang belum terisi atau salah. Kami telah mengarahkan Anda ke bagian yang perlu diperbaiki.',
                        confirmButtonText: 'Tutup',
                        confirmButtonColor: '#fe0000'
                    });
                }
            } catch (error) {
                console.error('Submission validation error:', error);
                Swal.fire('Error', 'Terjadi kesalahan saat memproses data.', 'error');
            } finally {
                submitBtn.disabled = false;
                submitBtn.innerHTML = originalText;
            }
        });
    }

    async validateAllSteps() {
        let firstErrorStep = null;
        let isAllValid = true;

        // We validate steps in order
        for (let i = 1; i <= this.steps.length; i++) {
            const isValid = await this.validateStep(i);
            if (!isValid) {
                isAllValid = false;
                if (!firstErrorStep) firstErrorStep = i;
            }
        }

        if (!isAllValid && firstErrorStep) {
            this.goToStep(firstErrorStep);
            
            // Scroll to the first invalid field in that step
            setTimeout(() => {
                const firstErrorInStep = document.getElementById(`step${firstErrorStep}`).querySelector('.is-invalid');
                if (firstErrorInStep) {
                    firstErrorInStep.scrollIntoView({ behavior: 'smooth', block: 'center' });
                    firstErrorInStep.focus();
                }
            }, 300);
        }

        return isAllValid;
    }

    initWizardNavigation() {
        this.nextButtons.forEach(btn => {
            btn.addEventListener('click', async () => {
                const currentStepNumber = parseInt(btn.dataset.next) - 1;
                
                // Show loading on button
                const originalText = btn.innerHTML;
                btn.disabled = true;
                btn.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span> Validating...';

                try {
                    const isValid = await this.validateStep(currentStepNumber);
                    if (isValid) {
                        this.goToStep(btn.dataset.next);
                    }
                } catch (error) {
                    console.error('Validation error:', error);
                    Swal.fire('Error', 'Terjadi kesalahan sistem saat validasi.', 'error');
                } finally {
                    btn.disabled = false;
                    btn.innerHTML = originalText;
                }
            });
        });

        this.prevButtons.forEach(btn => {
            btn.addEventListener('click', () => {
                this.goToStep(btn.dataset.prev);
            });
        });
    }

    async validateStep(stepNumber) {
        const step = document.getElementById(`step${stepNumber}`);
        const inputs = step.querySelectorAll('input, select, textarea');
        const formData = new FormData();
        
        formData.append('step', stepNumber);
        if (this.config.currentData.id) {
            formData.append('id', this.config.currentData.id);
        }

        inputs.forEach(input => {
            if (input.type === 'file') {
                if (input.files.length > 0) {
                    formData.append(input.name, input.files[0]);
                }
            } else {
                formData.append(input.name, input.value);
            }
        });

        // Add CSRF token
        const csrfToken = document.querySelector('meta[name="csrf-token"]').content;

        try {
            const response = await fetch(this.config.validationUrl, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json'
                },
                body: formData
            });

            const result = await response.json();

            // Clear previous errors
            inputs.forEach(input => {
                input.classList.remove('is-invalid');
                const feedback = input.parentNode.querySelector('.invalid-feedback');
                if (feedback) feedback.remove();
            });

            if (response.ok) {
                return true;
            } else {
                // Display server-side errors
                if (result.errors) {
                    Object.keys(result.errors).forEach(key => {
                        const input = this.form.querySelector(`[name="${key}"]`);
                        if (input) {
                            input.classList.add('is-invalid');
                            let feedback = input.parentNode.querySelector('.invalid-feedback');
                            if (!feedback) {
                                feedback = document.createElement('div');
                                feedback.className = 'invalid-feedback';
                                input.parentNode.appendChild(feedback);
                            }
                            feedback.textContent = result.errors[key][0];
                        }
                    });
                    
                    Swal.fire({
                        icon: 'error',
                        title: 'Validasi Gagal',
                        text: 'Ada beberapa isian yang belum benar. Silakan periksa kembali.',
                        toast: true,
                        position: 'top-end',
                        showConfirmButton: false,
                        timer: 4000
                    });

                    // Scroll to first error
                    const firstError = step.querySelector('.is-invalid');
                    if (firstError) {
                        firstError.scrollIntoView({ behavior: 'smooth', block: 'center' });
                    }
                }
                return false;
            }
        } catch (error) {
            console.error('Validation error:', error);
            throw error;
        }
    }

    goToStep(stepNumber) {
        this.steps.forEach(step => step.classList.remove('active'));
        const targetStep = document.getElementById(`step${stepNumber}`);
        if(targetStep) targetStep.classList.add('active');

        // Update Stepper UI
        this.stepperItems.forEach(item => {
            const itemStep = parseInt(item.dataset.step);
            if (itemStep < stepNumber) {
                item.classList.add('completed');
                item.classList.remove('active');
            } else if (itemStep == stepNumber) {
                item.classList.add('active');
                item.classList.remove('completed');
            } else {
                item.classList.remove('active', 'completed');
            }
        });
    }

    initImagePreview() {
        if (this.fotoInput && this.previewImg) {
            // Click on image to trigger file input
            this.previewImg.addEventListener('click', () => {
                this.fotoInput.click();
            });

            this.fotoInput.addEventListener('change', (event) => {
                const reader = new FileReader();
                reader.onload = () => {
                    this.previewImg.src = reader.result;
                };
                if (event.target.files[0]) {
                    reader.readAsDataURL(event.target.files[0]);
                }
            });
        }
    }

    async initRegionalCascading() {
        if (!this.provSelect) return;

        try {
            await this.loadProvinces();

            this.provSelect.addEventListener('change', () => this.handleProvChange());
            this.kabSelect.addEventListener('change', () => this.handleKabChange());
            this.kecSelect.addEventListener('change', () => this.handleKecChange());

            // Handle initial data if in edit mode or pre-filled from old input
            if (this.config.currentData.prov) {
                console.log('Auto-selecting regions for:', this.config.currentData);
                await this.autoSelectRegions();
            }
        } catch (error) {
            console.error('Failed to initialize regional data:', error);
        }
    }

    async loadProvinces() {
        const response = await fetch(`${this.config.baseUrl}/provinces.json`);
        const provinces = await response.json();
        provinces.forEach(prov => {
            let option = document.createElement('option');
            option.value = prov.name;
            option.dataset.id = prov.id;
            option.textContent = prov.name;
            if (this.config.currentData.prov === prov.name) option.selected = true;
            this.provSelect.appendChild(option);
        });
    }

    async handleProvChange() {
        const selectedOption = this.provSelect.options[this.provSelect.selectedIndex];
        const provId = selectedOption.dataset.id;
        this.resetDropdowns(['kab', 'kec', 'desa']);
        if (provId) {
            await this.loadRegencies(provId);
        }
    }

    async handleKabChange() {
        const selectedOption = this.kabSelect.options[this.kabSelect.selectedIndex];
        const kabId = selectedOption.dataset.id;
        this.resetDropdowns(['kec', 'desa']);
        if (kabId) {
            await this.loadDistricts(kabId);
        }
    }

    async handleKecChange() {
        const selectedOption = this.kecSelect.options[this.kecSelect.selectedIndex];
        const kecId = selectedOption.dataset.id;
        this.resetDropdowns(['desa']);
        if (kecId) {
            await this.loadVillages(kecId);
        }
    }

    async loadRegencies(provId) {
        const response = await fetch(`${this.config.baseUrl}/regencies/${provId}.json`);
        const regencies = await response.json();
        this.kabSelect.disabled = false;
        regencies.forEach(kab => {
            let option = document.createElement('option');
            option.value = kab.name;
            option.dataset.id = kab.id;
            option.textContent = kab.name;
            if (this.config.currentData.kab === kab.name) option.selected = true;
            this.kabSelect.appendChild(option);
        });
    }

    async loadDistricts(kabId) {
        const response = await fetch(`${this.config.baseUrl}/districts/${kabId}.json`);
        const districts = await response.json();
        this.kecSelect.disabled = false;
        districts.forEach(kec => {
            let option = document.createElement('option');
            option.value = kec.name;
            option.dataset.id = kec.id;
            option.textContent = kec.name;
            if (this.config.currentData.kec === kec.name) option.selected = true;
            this.kecSelect.appendChild(option);
        });
    }

    async loadVillages(kecId) {
        const response = await fetch(`${this.config.baseUrl}/villages/${kecId}.json`);
        const villages = await response.json();
        this.desaSelect.disabled = false;
        villages.forEach(desa => {
            let option = document.createElement('option');
            option.value = desa.name;
            option.dataset.id = desa.id;
            option.textContent = desa.name;
            if (this.config.currentData.desa === desa.name) option.selected = true;
            this.desaSelect.appendChild(option);
        });
    }

    resetDropdowns(keys) {
        keys.forEach(key => {
            const select = this[`${key}Select`];
            if(select) {
                const label = key.charAt(0).toUpperCase() + key.slice(1);
                select.innerHTML = `<option value="" selected disabled>Pilih ${label}</option>`;
                select.disabled = true;
            }
        });
    }

    async autoSelectRegions() {
        const provOption = Array.from(this.provSelect.options).find(o => o.value === this.config.currentData.prov);
        if (provOption) {
            this.provSelect.value = provOption.value;
            await this.loadRegencies(provOption.dataset.id);
            
            const kabOption = Array.from(this.kabSelect.options).find(o => o.value === this.config.currentData.kab);
            if (kabOption) {
                this.kabSelect.value = kabOption.value;
                await this.loadDistricts(kabOption.dataset.id);
                
                const kecOption = Array.from(this.kecSelect.options).find(o => o.value === this.config.currentData.kec);
                if (kecOption) {
                    this.kecSelect.value = kecOption.value;
                    await this.loadVillages(kecOption.dataset.id);
                    
                    const desaOption = Array.from(this.desaSelect.options).find(o => o.value === this.config.currentData.desa);
                    if (desaOption) {
                        this.desaSelect.value = desaOption.value;
                    }
                }
            }
        }
    }
}

window.AnggotaWizard = AnggotaWizard;
