<template>
    <div>
        <q-card flat>
            <q-card-section vertical>
                <div class="col-12 row">
                    <div class="col-md-3">
                        <q-select
                            dense
                            filled
                            :options="rows"
                            label="Assign user"
                            multiple
                            use-chips
                            />
                    </div>
                </div>
                <q-separator vertical />
                <q-card-section class="col-4">
                Lorem ipsum dolor sit amet, consectetur adipiscing elit.
                </q-card-section>
            </q-card-section>
            <q-card-section horizontal>
                <q-card-section>
                    Lorem
                </q-card-section>

                <q-separator vertical />

                <q-card-section class="col-4">
                Lorem ipsum dolor sit amet, consectetur adipiscing elit.
                </q-card-section>
            </q-card-section>
        </q-card>
    </div>
</template>