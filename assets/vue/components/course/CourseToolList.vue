<template>
  <div
      class=""
  >
    <div class="flex flex-col flex-center">
      <div class="p-9 rounded-xl shadow-lg">
        <a :href="goToCourseTool(course, tool)" class="">
            <v-icon
                :icon="tool.tool.icon"
                size="48px"
                class="font-extrabold text-transparent bg-clip-text bg-gradient-to-br from-ch-primary to-ch-primary-light"
            />
<!--          <img-->
<!--              :alt="tool.tool.name"-->
<!--              :src="'/img/tools/' + tool.tool.name + '.png'"-->
<!--              class="w-24 h-24 object-contain"-->
<!--          />-->
        </a>
      </div>

      <div class="flex flex-row gap-2 font-bold text-gray-500 pt-3">
        <a
            :href="goToCourseTool(course, tool)"
        >
<!--          {{ tool.ctool.nameToTranslate }} -->
          {{ $t(tool.tool.nameToShow) }}
        </a>

        <button v-if="isCurrentTeacher && changeVisibility" @click="changeVisibility(course, tool)">
          <v-icon
              v-if="tool.ctool.resourceNode.resourceLinks[0].visibility === 2"
              icon="mdi-eye" size="lg"
          />
          <v-icon
              v-else
              icon="mdi-eye-off"
              size="lg"
          />
        </button>

        <a
            v-if="isCurrentTeacher"
            :href="goToSettingCourseTool(course, tool)"
        >  <v-icon
            icon="mdi-cog" size="lg"
        />
        </a>
      </div>
    </div>
  </div>
</template>

<script>

import {mapGetters} from "vuex";

export default {
  name: 'CourseToolList',
  props: {
    course: Object,
    tool: Object,
    goToCourseTool: {
      type: Function,
      required: true
    },
    changeVisibility: {
      type: Function,
      required: false
    },
    goToSettingCourseTool: {
      type: Function,
      required: false
    },
  },
  computed: {
    ...mapGetters({
      'isCurrentTeacher': 'security/isCurrentTeacher',
    }),
  },
};
</script>
