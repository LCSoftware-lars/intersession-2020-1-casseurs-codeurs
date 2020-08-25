<template>
  <v-data-table
    :headers="headers"
    :items="results"
    :single-expand="singleExpand"
    :expanded.sync="expanded"
    :sort-by="['status', 'problem']"
    :sort-desc="[true, true]"
    item-key="name"
    show-expand
    class="elevation-1 colorPrimaryUltraLight"
  >
    <template v-slot:item.status="{ item }">
      <v-chip small :color="getColor(item.status)" class="caption my-2">{{ item.status }}</v-chip>
    </template>

    <template v-slot:expanded-item="{ headers, item }">
      <td :colspan="headers.length" class="pa-0">
        <v-data-table
          :headers="headersExpanded"
          :items="item.files"
          hide-default-footer
          class="colorGrey"
          :sort-by="['status', 'file', 'problem']"
          :sort-desc="[true, true, false]"
        >
          <template v-slot:item.status="{ item }">
            <v-chip small :color="getColor(item.status)" class="caption my-2">{{ item.status }}</v-chip>
          </template>
        </v-data-table>
      </td>
    </template>
  </v-data-table>
</template>

<script>
export default {
  name: "ResultTable",
  data() {
    return {
      expanded: [],
      singleExpand: true,
      headers: [
        {
          text: "Répertoire",
          align: "start",
          sortable: false,
          value: "name",
          class: "colorGreyDark colorSecondary--text",
        },
        {
          text: "Problèmes",
          value: "problem",
          class: "colorGreyDark colorSecondary--text",
        },
        {
          text: "Status",
          value: "status",
          class: "colorGreyDark colorSecondary--text",
        },
        {
          text: "",
          value: "data-table-expand",
          class: "colorGreyDark colorSecondary--text",
        },
      ],
      headersExpanded: [
        {
          text: "Nom du fichier",
          value: "file",
          class: "colorGreyDark colorTertiaryLight--text",
        },
        {
          text: "Problème",
          value: "problem",
          class: "colorGreyDark colorTertiaryLight--text",
        },
        {
          text: "Status",
          value: "status",
          class: "colorGreyDark colorTertiaryLight--text",
        },
      ],
      results: [
        {
          name: "Repo1",
          file: "my file1",
          problem: "delectus aut autem",
          status: "clean",
          criticity: 0,
          files: [
            {
              file: "my file1",
              problem: "delectus aut autem",
              status: "clean",
              criticity: 0,
            },
            {
              file: "my file2",
              problem: "delectus aut autem",
              status: "clean",
              criticity: 0,
            },
            {
              file: "my file3",
              problem: "delectus aut autem",
              status: "clean",
              criticity: 0,
            },
          ],
        },
        {
          name: "Repo2",
          file: "my file2",
          problem: "delectus aut autem",
          status: "warning",
          criticity: 1,
          files: [
            {
              file: "my file1",
              problem: "delectus aut autem",
              status: "warning",
              criticity: 1,
            },
            {
              file: "my file2",
              problem: "delectus aut autem",
              status: "clean",
              criticity: 0,
            },
            {
              file: "my file3",
              problem: "delectus aut autem",
              status: "clean",
              criticity: 0,
            },
          ],
        },
        {
          name: "Repo3",
          file: "my file3",
          problem: "delectus aut autem",
          status: "warning",
          criticity: 1,
          files: [
            {
              file: "my file1",
              problem: "delectus aut autem",
              status: "warning",
              criticity: 1,
            },
            {
              file: "my file2",
              problem: "delectus aut autem",
              status: "clean",
              criticity: 0,
            },
            {
              file: "my file3",
              problem: "delectus aut autem",
              status: "clean",
              criticity: 0,
            },
          ],
        },
        {
          name: "Repo4",
          file: "my file4",
          problem: "delectus aut autem",
          status: "danger",
          criticity: 2,
          files: [
            {
              file: "my file1",
              problem: "delectus aut autem",
              status: "warning",
              criticity: 1,
            },
            {
              file: "my file2",
              problem: "delectus aut autem",
              status: "danger",
              criticity: 2,
            },
            {
              file: "my file3",
              problem: "delectus aut autem",
              status: "clean",
              criticity: 0,
            },
          ],
        },
        {
          name: "Repo5",
          file: "my file5",
          problem: "delectus aut autem",
          status: "danger",
          criticity: 2,
          files: [
            {
              file: "my file1",
              problem: "delectus aut autem",
              status: "danger",
              criticity: 2,
            },
            {
              file: "my file2",
              problem: "delectus aut autem",
              status: "warning",
              criticity: 1,
            },
            {
              file: "my file3",
              problem: "delectus aut autem",
              status: "danger",
              criticity: 2,
            },
          ],
        },
        {
          name: "Repo6",
          file: "my file6",
          problem: "delectus aut autem",
          status: "warning",
          criticity: 1,
          files: [
            {
              file: "my file1",
              problem: "delectus aut autem",
              status: "warning",
              criticity: 1,
            },
            {
              file: "my file2",
              problem: "delectus aut autem",
              status: "clean",
              criticity: 0,
            },
            {
              file: "my file3",
              problem: "delectus aut autem",
              status: "clean",
              criticity: 0,
            },
          ],
        },
        {
          name: "Repo7",
          file: "my file7",
          problem: "delectus aut autem",
          status: "clean",
          criticity: 0,
          files: [
            {
              file: "my file1",
              problem: "delectus aut autem",
              status: "clean",
              criticity: 0,
            },
            {
              file: "my file2",
              problem: "delectus aut autem",
              status: "clean",
              criticity: 0,
            },
            {
              file: "my file3",
              problem: "delectus aut autem",
              status: "clean",
              criticity: 0,
            },
          ],
        },
        {
          name: "Repo8",
          file: "my file8",
          problem: "delectus aut autem",
          status: "danger",
          criticity: 2,
          files: [
            {
              file: "my file1",
              problem: "delectus aut autem",
              status: "danger",
              criticity: 2,
            },
            {
              file: "my file2",
              problem: "delectus aut autem",
              status: "clean",
              criticity: 0,
            },
            {
              file: "my file3",
              problem: "delectus aut autem",
              status: "danger",
              criticity: 2,
            },
          ],
        },
        {
          name: "Repo9",
          file: "my file9",
          problem: "delectus aut autem",
          status: "warning",
          criticity: 1,
          files: [
            {
              file: "my file1",
              problem: "delectus aut autem",
              status: "warning",
              criticity: 1,
            },
            {
              file: "my file2",
              problem: "delectus aut autem",
              status: "clean",
              criticity: 0,
            },
            {
              file: "my file3",
              problem: "delectus aut autem",
              status: "clean",
              criticity: 0,
            },
          ],
        },
        {
          name: "Repo10",
          file: "my file10",
          problem: "delectus aut autem",
          status: "clean",
          criticity: 0,
          files: [
            {
              file: "my file1",
              problem: "delectus aut autem",
              status: "clean",
              criticity: 0,
            },
            {
              file: "my file2",
              problem: "delectus aut autem",
              status: "clean",
              criticity: 0,
            },
            {
              file: "my file3",
              problem: "delectus aut autem",
              status: "clean",
              criticity: 0,
            },
          ],
        },
      ],
    };
  },

  methods: {
    getColor(status) {
      if (status === "danger") return "colorDanger";
      else if (status === "warning") return "colorWarning";
      else return "colorSecondaryLight";
    },
  },
};
</script>

<style scoped lang="scss">
.result {
  &--clean {
    border-left: 4px solid #9ade96;
  }
  &--warning {
    border-left: 4px solid orange;
  }
  &--danger {
    border-left: 4px solid tomato;
  }
}
.v-chip {
  display: inline-flex;
  align-items: center;
  justify-content: center;
  min-width: 10ch;
}

// .theme--dark.v-data-table .v-data-table-header th.sortable.active .v-data-table-header__icon,
// .theme--dark.v-data-table .v-data-table-header th.sortable .v-data-table-header__icon,
// .theme--dark.v-icon {
//     color: currentColor !important;
// }
</style>