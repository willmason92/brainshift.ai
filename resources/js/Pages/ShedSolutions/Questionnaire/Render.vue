<template>
    <layout-default :remove-footer="true">
        <div class="nanopixel-shed" id="nanopixelShed"></div>
    </layout-default>
</template>
<script>

    import LayoutDefault from '../../../Layout/Base.vue'
    import { Shed } from "etex-shed-configurator-main";
    import { useForm } from '@inertiajs/inertia-vue3';
    import { Inertia } from '@inertiajs/inertia';

    export default {
        components: {
            LayoutDefault,
        },
        props: {
            shedSolution: Object,
        },
        mounted() {
            let nanopixelShed = document.getElementById('nanopixelShed');

            const shed = new Shed(nanopixelShed);
            shed.set(this.getJsonConfig)
            shed.render();

            nanopixelShed.querySelector('[data-event="save"]').addEventListener(`click`, () => {
                shed.get3D().getImage().then((screenshot) => {
                    this.submitForm(shed, screenshot)
                })
            });
        },
        computed: {
          getJsonConfig() {
              //if theres a config, parse the string to Json Object, return json Object
              //else build a new object with values from questionnaire (in data)
              if (this.shedSolution.np_json_config) {
                  return JSON.parse(this.shedSolution.np_json_config);
            } else {
                  return {
                      name: 'Shed new',
                      width: this.shedSolution.width,
                      length: this.shedSolution.length,
                      type: (this.shedSolution.unit ? "feet" :  "meter"),
                      category: this.shedSolution.sector_name,
                  }
            }

            return this.shedSolution.np_json_config ?? this.data;
          },
        },
        methods: {
            submitForm(shed, image) {
                let form = useForm({
                    ...this.shedSolution,
                    np_name: shed.get().name,
                    np_image: image,
                    np_json_config: JSON.stringify(shed.get()),
                })
                if (this.shedSolution.unknownAge) { delete form.buildingAge }
                form.post(`/shed-solution/store-render` +
                    (this.shedSolution.solutionId ? '/' + this.shedSolution.solutionId : '')
                );
            }
        },
        data() {
            return {
                data: {
                    name: 'Shed #1',
                    width: this.shedSolution.width,
                    length: this.shedSolution.length,
                    type: (this.shedSolution.unit ? "feet" :  "meter"),
                    category: this.shedSolution.sector_name,
                }
            }
        },
    };
</script>
