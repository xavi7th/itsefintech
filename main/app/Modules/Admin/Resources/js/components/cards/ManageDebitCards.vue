<template>
  <main>
    <page-header pageTitle="Manage Debit Cards"></page-header>
    <div class="content">
      <!-- table basic -->
      <div class="card">
        <div class="card-body">
          <table class="table table-bordered table-hover" id="datatable1">
            <thead>
              <tr>
                <th>ID</th>
                <th>Card User Email</th>
                <th>Card Number</th>
                <th>Status</th>
                <th>Actions</th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="debitCard in debitCards" :key="debitCard.id">
                <td>{{ debitCard.id }}</td>
                <td>{{ debitCard.card_user.email }}</td>
                <td>{{ debitCard.card_number }}</td>
                <td>{{ hasExpired(debitCard.exp_date) ? 'Expired' : debitCard.is_suspended ? 'Card Suspended' : debitCard.is_admin_activated ? 'Fully Activated' : debitCard.is_user_activated ? 'Pending Confirmation' : 'Not Activated' }}</td>
                <td>
                  <div
                    class="fs-11 btn btn-bold badge badge-success badge-shadow pointer"
                    @click="activateDebitCard(debitCard)"
                    v-if="!debitCard.is_admin_activated && debitCard.is_user_activated"
                  >Activate Card</div>
                  <div
                    class="fs-11 btn btn-bold badge badge-primary pointer"
                    @click="toggleDebitCardSuspension(debitCard)"
                    v-if="debitCard.is_admin_activated && debitCard.is_user_activated && !debitCard.is_suspended"
                  >Suspend Card</div>
                  <div
                    class="fs-11 btn btn-bold badge badge-purple pointer"
                    @click="toggleDebitCardSuspension(debitCard)"
                    v-if="debitCard.is_suspended"
                  >Unsuspend Card</div>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </main>
</template>

<script>
  import {
    adminViewDebitCards,
    adminActivateDebitCard,
    toggleDebitCardSuspension
  } from "@admin-assets/js/config";
  import PreLoader from "@admin-components/misc/PageLoader";
  export default {
    name: "ManageDebitCards",
    data: () => ({
      debitCards: [],
      debitCardDetails: {},
      sectionLoading: false,
      details: {}
    }),
    components: {
      PreLoader
    },
    created() {
      this.getDebitCards();
    },
    mounted() {
      this.$emit("page-loaded");
    },
    methods: {
      slugToString(slug) {
        let words = slug.split("_");

        for (let i = 0; i < words.length; i++) {
          let word = words[i];
          words[i] = word.charAt(0).toUpperCase() + word.slice(1);
        }
        return words.join(" ");
      },
      getDebitCards() {
        BlockToast.fire({
          text: "loading debit cards..."
        });
        axios.get(adminViewDebitCards).then(({ data: { debit_cards } }) => {
          this.debitCards = debit_cards;

          this.$nextTick(() => {
            $(function() {
              $("#datatable1").DataTable({
                responsive: true,
                language: {
                  searchPlaceholder: "Search...",
                  sSearch: ""
                }
              });
            });
          });

          swal.close();
        });
      },
      activateDebitCard(debit_card) {
        this.sectionLoading = true;
        // BlockToast.fire({
        //   text: "updating card status ..."
        // });
        axios
          .put(adminActivateDebitCard(debit_card.id), {
            permitted_routes: this.permitted_routes
          })
          .then(({ status }) => {
            if (status === 204) {
              debit_card.is_admin_activated = true;
              Toast.fire({
                title: "Success",
                text: "Card activated",
                position: "center"
              });
            } else {
              Toast.fire({
                title: "Failed",
                text: "Something wrong happend",
                position: "center"
              });
            }

            this.$nextTick(() => {
              $(() => {
                this.sectionLoading = false;
              });
            });
          });
      },
      toggleDebitCardSuspension(debit_card) {
        this.sectionLoading = true;
        BlockToast.fire({
          text: "updating card status ..."
        });
        axios
          .put(toggleDebitCardSuspension(debit_card.id), {
            permitted_routes: this.permitted_routes
          })
          .then(({ status }) => {
            if (status === 204) {
              let msg = debit_card.is_suspended
                ? "Card has been unsuspended"
                : "Card has been suspended";
              debit_card.is_suspended = !debit_card.is_suspended;
              Toast.fire({
                title: "Success",
                text: msg,
                position: "center"
              });
            } else {
              Toast.fire({
                title: "Failed",
                text: "Something wrong happend",
                position: "center"
              });
            }

            this.$nextTick(() => {
              $(() => {
                this.sectionLoading = false;
              });
            });
          });
      },
      hasExpired(date) {
        return new Date(date) < Date.now();
      }
    }
  };
</script>

<style lang="scss" scoped>
  .modal-right,
  .modal-left {
    .modal-dialog {
      min-width: 35%;

      .modal-body {
        overflow-y: auto;
      }
    }
  }
  .section-loader {
    min-height: 90vh;
    margin-left: 0;
    position: fixed;
  }

  .form-group {
    position: relative;

    span {
      position: absolute;
      bottom: 7px;
      right: 20px;
      color: #d33;
      opacity: 0;
      transition: ease-in 300ms opacity;
      pointer-events: none;
    }

    &.has-error {
      .form-control {
        border-color: #f90a48;
        box-shadow: 0 0 10px rgba(249, 10, 72, 0.2);
      }

      > span {
        opacity: 1;
      }
    }
  }
</style>
