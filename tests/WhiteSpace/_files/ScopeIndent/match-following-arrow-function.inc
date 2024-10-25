<?php

final class ApprovalStep
{

    public function arrayMap(): void
    {
        $newApprovers = array_map(
            fn (Approver $approver) => $approver,
            $this
        );
    }

    public function match(): void
    {
        match ($action) {
            Action::APPROVE => $this->something()
        };
    }
}
